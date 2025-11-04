// Enhanced saveRecordingVideo with callback support for exam completion
// This script overrides the default saveFinalVideo function to support callback-based exam completion

// Override saveFinalVideo function to include callback support
function saveFinalVideoWithCallback() {
    // Prevent multiple calls
    if (window.isSavingVideo) {
        console.log('💾 saveFinalVideo already in progress, skipping...');
        return;
    }

    window.isSavingVideo = true;
    console.log('=== SAVING FINAL VIDEO WITH CALLBACK ===');
    console.log('Total chunks:', recordedChunks.length);
    console.log('MediaRecorder mimeType:', mediaRecorder?.mimeType);

    if (recordedChunks.length === 0) {
        console.warn('⚠️ NO VIDEO DATA TO SAVE');
        updateRecordingStatus('Completed', 'No data');
        // Call callback with false since no video to save
        if (window.examFinishCallback) {
            window.examFinishCallback(false);
        }
        return;
    }

    // Combine all chunks into final video
    const finalBlob = new Blob(recordedChunks, {
        type: mediaRecorder?.mimeType || 'video/webm'
    });

    const sizeInMB = (finalBlob.size / 1024 / 1024).toFixed(2);
    console.log('✅ Final video blob created!');
    console.log('Size:', finalBlob.size, 'bytes');
    console.log('Size in MB:', sizeInMB);
    console.log('Type:', finalBlob.type);

    if (finalBlob.size === 0) {
        console.warn('⚠️ FINAL VIDEO SIZE IS 0');
        updateRecordingStatus('Completed', 'Empty file');
        // Call callback with false since empty file
        if (window.examFinishCallback) {
            window.examFinishCallback(false);
        }
        return;
    }

    updateRecordingStatus('Saving', `Processing ${sizeInMB}MB...`);
    console.log('📤 Converting to base64...');

    const reader = new FileReader();

    reader.onload = function(e) {
        const base64Data = e.target.result;
        const base64Length = base64Data.length;
        console.log('✅ Base64 conversion complete');
        console.log('Base64 length:', base64Length);

        // Send final video to server with callback tracking
        if (window.Livewire) {
            console.log('📡 Sending final exam video to server with callback...');
            updateRecordingStatus('Saving', 'Uploading...');

            let saveCompleted = false;
            let callbackExecuted = false;

            // Function to execute callback only once
            function executeFinishCallback(success) {
                if (callbackExecuted) return;
                callbackExecuted = true;

                console.log('🔔 Executing finish callback with success:', success);

                if (window.examFinishCallback) {
                    window.examFinishCallback(success);
                } else {
                    console.warn('⚠️ No examFinishCallback found');
                }

                // Reset flags
                window.isRecordingStopping = false;
                window.isSavingVideo = false;
            }

            try {
                console.log('📡 Calling saveRecordingVideo with callback support...');

                // Method 1: Try component.call first (most reliable for large data)
                const component = document.querySelector('[wire\\:id]');
                if (component) {
                    const componentId = component.getAttribute('wire:id');
                    const livewireComponent = Livewire.find(componentId);

                    if (livewireComponent) {
                        console.log('📡 Using component call method...');
                        updateRecordingStatus('Saving', 'Component call...');

                        livewireComponent.call('saveRecordingVideo', base64Data)
                            .then((result) => {
                                console.log('✅ Component call response:', result);
                                if (result) {
                                    updateRecordingStatus('Completed', `Saved ${sizeInMB}MB`);
                                    console.log('✅ FINAL EXAM VIDEO SENT SUCCESSFULLY!');
                                    saveCompleted = true;
                                    executeFinishCallback(true);
                                } else {
                                    console.error('❌ Component call returned false');
                                    executeFinishCallback(false);
                                }
                            })
                            .catch((error) => {
                                console.error('❌ Component call failed:', error);
                                executeFinishCallback(false);
                            });
                    } else {
                        console.error('❌ Livewire component not found');
                        executeFinishCallback(false);
                    }
                } else {
                    console.error('❌ Wire element not found');
                    executeFinishCallback(false);
                }

                // Fallback dispatch method
                if (!component) {
                    console.log('📡 Using dispatch fallback...');
                    try {
                        Livewire.dispatch('saveRecordingVideo', {
                            videoBlob: base64Data
                        });

                        // For dispatch method, assume success after timeout
                        setTimeout(() => {
                            if (!callbackExecuted) {
                                updateRecordingStatus('Completed', 'Video dispatched');
                                console.log('📡 Dispatch method completed');
                                executeFinishCallback(true);
                            }
                        }, 3000);

                    } catch (dispatchError) {
                        console.error('❌ Dispatch failed:', dispatchError);
                        executeFinishCallback(false);
                    }
                }

                // Safety timeout - if nothing happens after 20 seconds
                setTimeout(() => {
                    if (!callbackExecuted) {
                        console.warn('⚠️ Save timeout reached, proceeding anyway...');
                        updateRecordingStatus('Timeout', 'Proceeding...');
                        executeFinishCallback(false);
                    }
                }, 20000);

            } catch (error) {
                console.error('❌ Error calling saveRecordingVideo:', error);
                updateRecordingStatus('Error', 'Call failed');
                executeFinishCallback(false);
            }
        } else {
            console.error('❌ Livewire not available');
            updateRecordingStatus('Error', 'No connection');
            executeFinishCallback(false);
        }

        // Clear chunks after processing
        recordedChunks = [];
        console.log('🗑️ Chunks cleared from memory');
    };

    reader.onerror = function(error) {
        console.error('❌ FileReader error:', error);
        updateRecordingStatus('Error', 'Read failed');

        // Call callback with error
        if (window.examFinishCallback) {
            window.examFinishCallback(false);
        }

        // Reset flags
        window.isRecordingStopping = false;
        window.isSavingVideo = false;
        recordedChunks = [];
    };

    console.log('🔄 Starting base64 conversion...');
    reader.readAsDataURL(finalBlob);
}

// Override the original stopRecording to use callback version
function stopRecordingWithCallback() {
    // Prevent multiple calls
    if (window.isRecordingStopping) {
        console.log('🛑 stopRecording already in progress, skipping...');
        return;
    }

    window.isRecordingStopping = true;
    console.log('=== STOPPING RECORDING WITH CALLBACK ===');
    console.log('MediaRecorder state:', mediaRecorder?.state);
    console.log('IsRecording flag:', isRecording);
    console.log('Recorded chunks length:', recordedChunks?.length || 0);

    if (mediaRecorder && mediaRecorder.state === 'recording') {
        console.log('Stopping MediaRecorder...');

        // Set a timeout fallback in case onstop doesn't fire
        const fallbackTimeout = setTimeout(() => {
            console.warn('MediaRecorder onstop did not fire, manually saving video');
            saveFinalVideoWithCallback();
        }, 3000); // 3 second fallback

        mediaRecorder.onstop = function() {
            console.log('MediaRecorder onstop event fired');
            clearTimeout(fallbackTimeout);
            saveFinalVideoWithCallback();
        };

        mediaRecorder.stop();
        isRecording = false;
        clearInterval(recordingDurationInterval);
        stopPeriodicBackup();
        updateRecordingStatus('Stopping', 'Saving video...');
        console.log('MediaRecorder.stop() called');

    } else if (recordedChunks && recordedChunks.length > 0) {
        console.log('No active MediaRecorder but we have chunks, saving directly...');
        saveFinalVideoWithCallback();
    } else {
        console.warn('No active recording and no chunks to save');
        updateRecordingStatus('Completed', 'No data');

        // Still call callback even if no recording
        if (window.examFinishCallback) {
            window.examFinishCallback(false);
        }
    }
}

// Replace the original functions
if (typeof stopRecording !== 'undefined') {
    // Backup original function
    window.originalStopRecording = stopRecording;
}
window.stopRecording = stopRecordingWithCallback;

if (typeof saveFinalVideo !== 'undefined') {
    // Backup original function
    window.originalSaveFinalVideo = saveFinalVideo;
}
window.saveFinalVideo = saveFinalVideoWithCallback;

console.log('✅ Callback-based recording functions loaded');
console.log('📝 Available functions: stopRecording(), saveFinalVideo()');
console.log('🔗 Callback system: window.examFinishCallback will be called after video save');
