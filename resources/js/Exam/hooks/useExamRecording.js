import { useState, useEffect, useRef, useCallback } from 'react';
import axios from 'axios';

const CHUNK_INTERVAL_MS = 30 * 1000; // 30 detik per chunk

export const useExamRecording = (userTimetableId, isEnabled, sharedStream) => {
    const mediaRecorderRef = useRef(null);
    const streamRef = useRef(null);
    const chunkNumberRef = useRef(1);
    const chunkIntervalRef = useRef(null);
    const mimeTypeRef = useRef('');
    const isStoppingRef = useRef(false);
    const hasStartedRef = useRef(false);
    const [isRecording, setIsRecording] = useState(false);

    const getSupportedMimeType = () => {
        const types = [
            'video/webm;codecs=vp9,opus',
            'video/webm;codecs=vp8,opus',
            'video/webm',
            'video/mp4;codecs=h264',
            'video/mp4'
        ];
        for (const type of types) {
            if (MediaRecorder.isTypeSupported(type)) return type;
        }
        return '';
    };

    /**
     * Upload a single chunk blob to the server.
     */
    const uploadChunk = useCallback(async (blob, chunkNumber) => {
        const formData = new FormData();
        formData.append('user_timetable_id', userTimetableId);
        formData.append('chunkBlob', blob, `chunk_${chunkNumber}.webm`);
        formData.append('chunkNumber', chunkNumber);

        try {
            await axios.post('/api/exam/recording/upload-chunk', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            console.log(`[Recording] Chunk ${chunkNumber} uploaded.`);
        } catch (err) {
            console.error(`[Recording] Failed to upload chunk ${chunkNumber}:`, err);
        }
    }, [userTimetableId]);

    /**
     * Create a new MediaRecorder session using the existing stream.
     */
    const startNewRecorderSession = useCallback(() => {
        if (!streamRef.current || isStoppingRef.current) return;

        const mimeType = mimeTypeRef.current;
        const recorder = new MediaRecorder(streamRef.current, { mimeType });
        const chunks = [];

        recorder.ondataavailable = (e) => {
            if (e.data && e.data.size > 0) chunks.push(e.data);
        };

        recorder.onstop = async () => {
            if (chunks.length > 0) {
                const blob = new Blob(chunks, { type: mimeType });
                const currentChunk = chunkNumberRef.current;
                chunkNumberRef.current += 1;
                await uploadChunk(blob, currentChunk);
            }
        };

        recorder.start();
        mediaRecorderRef.current = recorder;
    }, [uploadChunk]);

    /**
     * Stop the current recorder session and start a new one (chunking logic).
     */
    const rotateChunk = useCallback(() => {
        if (isStoppingRef.current) return;
        const recorder = mediaRecorderRef.current;
        if (recorder && recorder.state === 'recording') {
            recorder.stop(); // onstop will upload and increment chunkNumber
        }
        // Start fresh session after a small delay to let onstop handle data
        setTimeout(() => {
            if (!isStoppingRef.current) startNewRecorderSession();
        }, 200);
    }, [startNewRecorderSession]);

    /**
     * Begin recording: get camera stream, start first chunk, set rotation interval.
     */
    const startRecording = useCallback(async () => {
        if (!isEnabled || hasStartedRef.current) return;
        hasStartedRef.current = true;
        isStoppingRef.current = false;

        try {
            let stream = sharedStream;
            if (!stream) {
                stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            }
            streamRef.current = stream;

            const mimeType = getSupportedMimeType();
            if (!mimeType) {
                console.error('[Recording] No supported MIME type found.');
                return;
            }
            mimeTypeRef.current = mimeType;
            chunkNumberRef.current = 1;

            startNewRecorderSession();
            setIsRecording(true);
            console.log('[Recording] Started. Chunking every 30s with MIME:', mimeType);

            // Set up rotation interval
            chunkIntervalRef.current = setInterval(rotateChunk, CHUNK_INTERVAL_MS);

        } catch (err) {
            console.error('[Recording] Error starting recording:', err);
            hasStartedRef.current = false;
        }
    }, [isEnabled, startNewRecorderSession, rotateChunk, sharedStream]);

    /**
     * Stop all recording, upload last chunk, then request server to merge with FFmpeg.
     */
    const stopRecording = useCallback(() => {
        return new Promise((resolve) => {
            isStoppingRef.current = true;
            hasStartedRef.current = false;

            // Clear the rotation interval
            if (chunkIntervalRef.current) {
                clearInterval(chunkIntervalRef.current);
                chunkIntervalRef.current = null;
            }

            const recorder = mediaRecorderRef.current;
            const mimeType = mimeTypeRef.current;

            const finalize = async () => {
                // Stop camera tracks only if not using shared stream
                if (!sharedStream) {
                    streamRef.current?.getTracks().forEach(t => t.stop());
                }
                streamRef.current = null;
                setIsRecording(false);

                // Trigger server-side FFmpeg merge
                try {
                    console.log('[Recording] Requesting server merge via FFmpeg...');
                    await axios.post('/api/exam/recording/merge', {
                        user_timetable_id: userTimetableId
                    });
                    console.log('[Recording] Merge request sent successfully.');
                } catch (err) {
                    console.error('[Recording] Merge request failed:', err);
                }
                resolve();
            };

            if (!recorder || recorder.state === 'inactive') {
                finalize();
                return;
            }

            // Capture last chunk before stopping
            const pendingChunks = [];
            recorder.ondataavailable = (e) => {
                if (e.data && e.data.size > 0) pendingChunks.push(e.data);
            };
            recorder.onstop = async () => {
                if (pendingChunks.length > 0) {
                    const blob = new Blob(pendingChunks, { type: mimeType });
                    await uploadChunk(blob, chunkNumberRef.current);
                }
                await finalize();
            };

            recorder.stop();
        });
    }, [uploadChunk, userTimetableId, sharedStream]);


    // Auto-start and cleanup
    useEffect(() => {
        if (isEnabled && !hasStartedRef.current && sharedStream) {
            startRecording();
        }

        return () => {
            if (isStoppingRef.current) return;
            isStoppingRef.current = true;
            hasStartedRef.current = false;
            if (chunkIntervalRef.current) clearInterval(chunkIntervalRef.current);
            const recorder = mediaRecorderRef.current;
            if (recorder && recorder.state !== 'inactive') recorder.stop();
            if (!sharedStream) {
                streamRef.current?.getTracks().forEach(t => t.stop());
            }
        };
    }, [isEnabled, sharedStream, startRecording]);

    return { isRecording, stopRecording };
};
