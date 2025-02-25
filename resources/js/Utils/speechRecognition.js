export function useSpeechRecognition() {
  const transcript = ref("");
  const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();

  recognition.continuous = true;
  recognition.interimResults = true;

  recognition.onresult = (event) => {
    transcript.value = Array.from(event.results)
      .map((result) => result[0].transcript)
      .join("");
  };

  const startListening = () => {
    recognition.start();
  };

  const stopListening = () => {
    recognition.stop();
  };

  return {
    transcript,
    startListening,
    stopListening,
  };
}
