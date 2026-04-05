import { useState, useEffect, useRef, useCallback } from 'react';
import axios from 'axios';

export const useExamRecording = (userTimetableId, isEnabled) => {
    const mediaRecorderRef = useRef(null);
    const [isRecording, setIsRecording] = useState(false);
    const chunkNumberRef = useRef(1);
    const streamRef = useRef(null);
    const chunkIntervalRef = useRef(null);


    const getSupportedMimeType = () => {
        const types = [
            'video/webm;codecs=vp9,opus',
            'video/webm;codecs=vp8,opus',
            'video/webm',
            'video/mp4;codecs=h264',
            'video/mp4'
        ];
        for (const type of types) {
            if (MediaRecorder.isTypeSupported(type)) {
                return type;
            }
        }
        return '';
    };

    const uploadChunk = useCallback(async (blob) => {
        const formData = new FormData();
        formData.append('user_timetable_id', userTimetableId);
        formData.append('chunkBlob', blob, `chunk_${chunkNumberRef.current}.webm`);
        formData.append('chunkNumber', chunkNumberRef.current++);

        try {
            await axios.post('/api/exam/recording/chunk', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            console.log(`Chunk ${chunkNumberRef.current - 1} uploaded successfully`);
        } catch (error) {
            console.error("Failed to upload recording chunk", error);
        }
    }, [userTimetableId]);

    const startRecording = useCallback(async () => {
        if (!isEnabled) return;
        
        try {
            const stream = streamRef.current || await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            streamRef.current = stream;
            
            const mimeType = getSupportedMimeType();
            if (!mimeType) {
                console.error("No supported MIME type found for MediaRecorder");
                return;
            }

            const startIntervalRecording = () => {
                const mediaRecorder = new MediaRecorder(stream, { mimeType });
                
                mediaRecorder.ondataavailable = (event) => {
                    if (event.data && event.data.size > 0) {
                        uploadChunk(event.data);
                    }
                };

                mediaRecorder.start();
                mediaRecorderRef.current = mediaRecorder;
                setIsRecording(true);
            };

            // Start first interval
            startIntervalRecording();
            console.log("Recording started with MIME type:", mimeType);

            // Periodically reset the recorder to create standalone valid chunks
            chunkIntervalRef.current = setInterval(() => {
                if (mediaRecorderRef.current && mediaRecorderRef.current.state === 'recording') {
                    // This triggers ondataavailable synchronously
                    mediaRecorderRef.current.stop();
                    // Immediately start a new valid recording chunk
                    startIntervalRecording();
                }
            }, 30000); // Create a new valid chunk every 30 seconds

        } catch (err) {
            console.error("Error starting recording:", err);
        }
    }, [isEnabled, uploadChunk]);

    const stopRecording = useCallback(async () => {
        if (chunkIntervalRef.current) {
            clearInterval(chunkIntervalRef.current);
            chunkIntervalRef.current = null;
        }

        if (mediaRecorderRef.current && mediaRecorderRef.current.state !== 'inactive') {
            // Signal stop - this will trigger one last ondataavailable
            mediaRecorderRef.current.stop();
            setIsRecording(false);
            
            // Stop all tracks to release camera
            if (streamRef.current) {
                streamRef.current.getTracks().forEach(track => track.stop());
                streamRef.current = null;
            }
            
            // Wait slightly for the last ondataavailable to finish the uploadChunk call
            // before telling the server to finalize everything.
            setTimeout(() => {
                axios.post(`/api/exam/recording/finalize/${userTimetableId}`)
                    .then(() => console.log("Finalization request sent"))
                    .catch(err => console.error("Failed to finalize recording", err));
            }, 1000);
        }
    }, [userTimetableId]);

    useEffect(() => {
        if (isEnabled && !isRecording) {
            startRecording();
        }
        return () => {
            if (isRecording) stopRecording();
        };
    }, [isEnabled, isRecording, startRecording, stopRecording]);

    return { isRecording, stopRecording };
};
