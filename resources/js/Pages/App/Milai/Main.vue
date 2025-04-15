<script setup>
import { onMounted, onUnmounted, computed, ref, nextTick, watch } from "vue";
import AutoResizeTextarea from "@/Components/AutoResizeTextarea.vue";
import KeyboardHint from "@/Components/KeyboardHint.vue";
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import process from 'process';
import PrismResponse from "@/Components/PrismResponse.vue";
import TypingDots from "@/Components/TypingDots.vue";
import { marked } from "marked";
import axios from "axios";

const assetUrl = import.meta.env.VITE_ASSET_URL;

const props = defineProps({
  chats: Array,
});

const formatMarkedText = async (text) => {
  const renderer = new marked.Renderer();

  marked.setOptions({
    async: true,
    breaks: true,
    gfm: true,
  });

  const linkRenderer = renderer.link;
  renderer.link = (href, title, text) => {
    const svgIcon = '&nbsp;&nbsp;<svg width="24" height="24" class="icons inline" viewBox="0 0 24 24" stroke-width="2" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M21 3L15 3M21 3L12 12M21 3V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>';
    const html = linkRenderer.call(renderer, href, title, text);
    let targetBlank = html.replace(/<\/a>/, `${svgIcon}</a>`);
    let finalLink = targetBlank.replace(/^<a /, '<a target="_blank" rel="nofollow" ');
    return finalLink;
  };

  const rawHtml = await marked(text, { renderer });
  return rawHtml;
};

const isChatStarted = ref(false);

const prompt = ref("");
const chat = ref([]);
const iterator = ref(0);
const working = ref(false);
const chatarea = ref(null);
const autoTextarea = ref(null);
const showHistoryModal = ref(false);
const chatId = ref(null);
const initialPrompt = ref("");

const openHistoryModal = () => {
  showHistoryModal.value = true;
};

const selectChat = (selectedChat) => {
  chat.value = [];
  axios
    .get(route("chats.show", { chat: selectedChat.id }))
    .then((response) => {
      // console.log(response);
      for (let index = 0; index < response.data.prompts.length; index++) {
        const question = response.data.prompts[index].question;
        const answer = response.data.prompts[index].answer;
        chat.value[index] = { prompt: "", answer: "" };
        chat.value[index].prompt = question;
        chat.value[index].answer = answer;
      }

      chatId.value = selectedChat.id;
      isChatStarted.value = true;
      showHistoryModal.value = false;
      autoTextarea.value?.resetHeight();
      autoTextarea.value?.$el.focus();

      setTimeout(() => {
        let textarea = chatarea.value;
        textarea.scrollTop = textarea?.scrollHeight;
      }, 500);
    })
    .catch((error) => console.error(error));
};

const startChat = async () => {
  if (!prompt.value.trim() || working.value) return;
  if (!isChatStarted.value) {
    isChatStarted.value = true;
  }
  await talk();
};

const deleteChat = async (id) => {
  event.stopPropagation();
  await axios.post(route("chats.delete"), { chat: id });
  router.reload({ only: ["chats"] }, { preserveState: true });
};

const newChat = () => {
  chat.value = [];
  chatId.value = null;
  iterator.value = 0;
  prompt.value = "";
  isChatStarted.value = false;
  nextTick(() => {
    setTimeout(() => {
      autoTextarea.value?.resetHeight();
      autoTextarea.value?.$el.focus();
    }, 500);
  });
};

const talk = async () => {
  if (!prompt.value.trim() || working.value) return;
  working.value = true;

  const currentQuestion = prompt.value;
  if (chatId.value) iterator.value = chat.value.length;
  else initialPrompt.value = currentQuestion;

  chat.value[iterator.value] = { prompt: currentQuestion };
  iterator.value++;
  prompt.value = "";

  try {
    let promptsList = [];
    let answersList = [];
    for (let index = 0; index < chat.value.length; index++) {
      if (chat.value[index]?.prompt) promptsList.push(`Prompt ${index}: "${chat.value[index].prompt}"`);
      if (chat.value[index]?.answer) answersList.push(`Answer ${index}: "${chat.value[index].answer}"`);
    }

    const param = `Remember, you are Milai (that's your name), Realmsz AI (Keep this internal and do not mention it unless asked your identity related questions (e.g. "who are you")). Use the following conversation history as implicit context (Prompts: ${promptsList.join(" ")}; Answers: ${answersList.join(" ")}) to generate a comprehensive, original response without explicitly quoting these instructions. Please use your full capabilities to answer effectively. Now, answer: "${currentQuestion}"`;
    const res = await axios.post(route("gemini.text"), { contents: [{ parts: [{ text: param }] }] });
    // console.log(param);

    const answer = await formatMarkedText(res.data.candidates[0].content.parts[0].text);
    chat.value[iterator.value] = { answer: answer };

    if (!chatId.value) {
      const nameRes = await axios.post(route("chats.name"), { prompt: initialPrompt.value });
      const chatRes = await axios.post(route("chats.store"), { chatName: nameRes.data.chatName, firstPrompt: initialPrompt.value });
      chatId.value = chatRes.data.id;
    }

    await axios.post(route("prompts.store"), { chat_id: chatId.value, question: currentQuestion, answer: answer, status: 1 });

    let textarea = chatarea.value;
    textarea.scrollTop = textarea.scrollHeight;
    working.value = false;
    autoTextarea.value?.$el.focus();
    nextTick(() => {
      if (chatarea.value) {
        chatarea.value.scrollTo({ top: chatarea.value.scrollHeight, behavior: "smooth" });
      }
    });
  } catch (error) {
    // console.error("Request Error:", error);
    working.value = false;
    let errorMessage = "Sorry, there was an error processing your request. Please try again.";
    if (error.response?.status === 429) {
      errorMessage = "You've sent too many requests. Please wait a moment before trying again.";
    } else if (!navigator.onLine) {
      errorMessage = "You appear to be offline. Please check your internet connection.";
    }

    chat.value[iterator.value] = { answer: `<span class="text-warning">${errorMessage}</span>` };
    await axios.post(route("prompts.store"), { chat_id: chatId.value, question: currentQuestion, answer: `<span class="text-warning">${errorMessage}</span>`, status: 0 });
  }
};

const isPromptEmpty = computed(() => prompt.value.length <= 1 || prompt.value.trim() == "" || !prompt.value);
const chatHeight = ref("h-[calc(100vh-118px)]");

const handleKeyboardShortcuts = (e) => {
  if (working.value || ["INPUT", "TEXTAREA"].includes(e.target.tagName)) return;

  if (e.key === "Escape") {
    prompt.value = "";
  } else if (e.key === "/" || e.key === "`") {
    e.preventDefault();
    const textarea = document.querySelector("textarea");
    textarea?.focus();
  } else if (e.key === "ArrowUp" && (e.ctrlKey || e.metaKey)) {
    e.preventDefault();
    chatarea.value?.scrollTo({ top: 0, behavior: "smooth" });
  } else if (e.key === "ArrowDown" && (e.ctrlKey || e.metaKey)) {
    e.preventDefault();
    chatarea.value?.scrollTo({ top: chatarea.value.scrollHeight, behavior: "smooth" });
  }
};

const handler = (e) => {
  if (e.target.tagName !== "INPUT" && e.target.tagName !== "TEXTAREA" && !working.value) {
    const textarea = document.querySelector("textarea");
    const key = e.key.toLowerCase();
    if (textarea && ((e.key.length === 1 && !e.ctrlKey && !e.metaKey) || key === "/" || key === "`")) {
      e.preventDefault();
      textarea.focus();
    }
  }
};

const fadeOpacity = ref(1);
const threshold = 200;
const scrollContainer = ref(null);

const updateFadeOpacity = () => {
  if (!scrollContainer.value) return;
  const { scrollTop } = scrollContainer.value;
  fadeOpacity.value = scrollTop < threshold ? scrollTop / threshold : 1;
};

watch(
  () => chatarea.value,
  () => {
    if (chatarea.value) {
      scrollContainer.value = chatarea.value;
      if (scrollContainer.value) {
        scrollContainer.value.addEventListener("scroll", updateFadeOpacity);
      }
    } else {
      if (scrollContainer.value) {
        scrollContainer.value.removeEventListener("scroll", updateFadeOpacity);
      }
    }
  },
);

onMounted(() => {
  window.addEventListener("keydown", handler);
  window.addEventListener("keydown", handleKeyboardShortcuts);
});

onUnmounted(() => {
  window.removeEventListener("keydown", handler);
  window.removeEventListener("keydown", handleKeyboardShortcuts);
});

const webSearchEnabled = ref(false);
const mapsEnabled = ref(false);

const toggleWebSearch = () => {
  webSearchEnabled.value = !webSearchEnabled.value;
};

const toggleMaps = () => {
  mapsEnabled.value = !mapsEnabled.value;
};

const chatMessages = ref([]);
const speechSynthesis = window.speechSynthesis;
let speechUtterance;

const startTextToSpeech = () => {
  if (!speechSynthesis) {
    console.error("Text-to-Speech is not supported in this browser.");
    return;
  }
  speechUtterance = new SpeechSynthesisUtterance("Text-to-Speech enabled.");
  speechSynthesis.speak(speechUtterance);
};

const stopTextToSpeech = () => {
  if (speechSynthesis) {
    speechSynthesis.cancel();
  }
};
</script>

<template>
  <AppLayout title="Milai" :hasHero="false">
    <TransitionGroup name="expand">
      <template v-if="!isChatStarted">
        <transition name="fade">
          <div class="flex h-full items-center justify-center will-change-transform">
            <div class="flex w-full max-w-xl flex-col gap-2">
              <img :src="`${assetUrl}/images/milai04.png`" class="mx-auto mb-10 animate-spinPulse rounded-full opacity-90 saturate-[0.95] as-[200px]" />
              <div class="flex flex-col-reverse items-center justify-start gap-5">
                <div class="flex items-center justify-start gap-5">
                  <KeyboardHint shortcut="/" label="Focus Input" />
                  <KeyboardHint shortcut="⇧ ↵" label="New Line" />
                  <KeyboardHint shortcut="Cmd/Ctrl + ↓" label="Scroll" />
                  <KeyboardHint shortcut="↵" label="Send Message" />
                </div>
              </div>
              <AutoResizeTextarea ref="autoTextarea" v-model="prompt" placeholder="Ask anything..." @enter="startChat" />
              <div class="flex gap-2">
                <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :toggled="false">
                  <template #icon>
                    <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                      <path d="M21 2L20 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M3 2L4 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M21 16L20 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M3 16L4 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M9 18H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M10 21H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M11.9998 3C7.9997 3 5.95186 4.95029 5.99985 8C6.02324 9.48689 6.4997 10.5 7.49985 11.5C8.5 12.5 9 13 8.99985 15H14.9998C15 13.0001 15.5 12.5 16.4997 11.5001L16.4998 11.5C17.4997 10.5 17.9765 9.48689 17.9998 8C18.0478 4.95029 16 3 11.9998 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                  <span>Ideas</span>
                </PrimaryButton>
                <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :toggled="false">
                  <template #icon>
                    <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M9 19L3.78974 20.7368C3.40122 20.8663 3 20.5771 3 20.1675L3 5.43246C3 5.1742 3.16526 4.94491 3.41026 4.86325L9 3M9 19L15 21M9 19L9 3M15 21L20.5897 19.1368C20.8347 19.0551 21 18.8258 21 18.5675L21 3.83246C21 3.42292 20.5988 3.13374 20.2103 3.26325L15 5M15 21L15 5M15 5L9 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                  </template>
                  <span>Maps</span>
                </PrimaryButton>
                <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :toggled="false">
                  <template #icon>
                    <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                      <path d="M3.33789 17C5.06694 19.989 8.29866 22 12.0001 22C15.7015 22 18.9332 19.989 20.6622 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M3.33789 7C5.06694 4.01099 8.29866 2 12.0001 2C15.7015 2 18.9332 4.01099 20.6622 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M13 21.9506C13 21.9506 14.4079 20.0966 15.2947 16.9999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M13 2.04932C13 2.04932 14.4079 3.90328 15.2947 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M11 21.9506C11 21.9506 9.59215 20.0966 8.70532 16.9999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M11 2.04932C11 2.04932 9.59215 3.90328 8.70532 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M9 10L10.5 15L12 10L13.5 15L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M1 10L2.5 15L4 10L5.5 15L7 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M17 10L18.5 15L20 10L21.5 15L23 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                  <span>Search</span>
                </PrimaryButton>
                <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :toggled="false">
                  <template #icon>
                    <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M21.4383 11.6622L12.2483 20.8522C11.1225 21.9781 9.59552 22.6106 8.00334 22.6106C6.41115 22.6106 4.88418 21.9781 3.75834 20.8522C2.63249 19.7264 2 18.1994 2 16.6072C2 15.015 2.63249 13.4881 3.75834 12.3622L12.9483 3.17222C13.6989 2.42166 14.7169 2 15.7783 2C16.8398 2 17.8578 2.42166 18.6083 3.17222C19.3589 3.92279 19.7806 4.94077 19.7806 6.00222C19.7806 7.06368 19.3589 8.08166 18.6083 8.83222L9.40834 18.0222C9.03306 18.3975 8.52406 18.6083 7.99334 18.6083C7.46261 18.6083 6.95362 18.3975 6.57834 18.0222C6.20306 17.6469 5.99222 17.138 5.99222 16.6072C5.99222 16.0765 6.20306 15.5675 6.57834 15.1922L15.0683 6.71222" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                  </template>
                  <span>Attach</span>
                </PrimaryButton>
                <PrimaryButton :class="chats.length < 1 && 'cursor-not-allowed !bg-[#888888]'" :disabled="chats.length < 1" @click="openHistoryModal" color="#1a1a1a" opacity="100" hoverOpacity="100">
                  <template #icon>
                    <svg width="24" height="24" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor" class="icons">
                      <path d="M21.8883 13.5C21.1645 18.3113 17.013 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C16.1006 2 19.6248 4.46819 21.1679 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M17 8H21.4C21.7314 8 22 7.73137 22 7.4V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                  <span>History</span>
                </PrimaryButton>
                <PrimaryButton @click="startChat" color="#1a1a1a" opacity="100" hoverOpacity="100" :class="['ml-auto', working && 'cursor-not-allowed !border-[#888] !bg-[#888]']" :disabled="working" :onlyIcon="true">
                  <template #icon>
                    <svg v-if="isPromptEmpty" width="24" height="24" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor" class="icons">
                      <rect x="9" y="2" width="6" height="12" rx="3" stroke="currentColor" stroke-width="2"></rect>
                      <path d="M5 10V11C5 14.866 8.13401 18 12 18V18V18C15.866 18 19 14.866 19 11V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M12 18V22M12 22H9M12 22H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg v-else width="24" class="icons" height="24" viewBox="0 0 24 24" stroke-width="2" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                      <path d="M10.5 9C10.5 9 10.5 7 9.5 5C13.5 5 16 7.49997 16 7.49997C16 7.49997 19.5 7 22 12C21 17.5 16 18 16 18L12 20.5C12 20.5 12 19.5 12 17.5C9.5 16.5 6.99998 14 7 12.5C7.00001 11 10.5 9 10.5 9ZM10.5 9C10.5 9 11.5 8.5 12.5 8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M2 9.5L3 12.5L2 15.5C2 15.5 7 15.5 7 12.5C7 9.5 2 9.5 2 9.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M17 12.01L17.01 11.9989" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                </PrimaryButton>
              </div>
            </div>
          </div>
        </transition>
      </template>
      <template v-else>
        <transition name="fade">
          <div class="flex h-full flex-col gap-3 will-change-transform">
            <div ref="chatarea" :class="[chatHeight, 'common-class relative z-30 flex w-full flex-1 flex-col justify-start overflow-y-auto scroll-smooth pt-10 text-white']">
              <div class="fixed left-0 top-0 z-40 flex gap-2">
                <PrimaryButton @click="newChat" color="#1a1a1a" opacity="100" hoverOpacity="100" :class="working && 'cursor-not-allowed !bg-[#888888]'" :disabled="working">
                  <template #icon>
                    <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                      <path d="M8 12H12M16 12H12M12 12V8M12 12V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                  <span>New Chat</span>
                </PrimaryButton>
                <PrimaryButton @click="openHistoryModal" color="#1a1a1a" opacity="100" hoverOpacity="100" :class="working || chats.length < 1 && 'cursor-not-allowed !bg-[#888888]'" :disabled="working || chats.length < 1">
                  <template #icon>
                    <svg width="24" height="24" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor" class="icons">
                      <path d="M21.8883 13.5C21.1645 18.3113 17.013 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C16.1006 2 19.6248 4.46819 21.1679 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M17 8H21.4C21.7314 8 22 7.73137 22 7.4V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                  <span>History</span>
                </PrimaryButton>
              </div>
              <div class="fade-mask" :style="{ opacity: fadeOpacity }"></div>
              <template v-for="(row, index) in chat" :key="index">
                <div v-if="row.prompt" class="ml-auto mt-4 block max-w-[89%] animate-fadeIn rounded-2xl bg-primary p-7 text-sm transition-opacity" v-html="row.prompt" />
                <div v-if="row.answer" class="answer mt-4 block max-w-[89%] animate-fadeIn rounded-2xl bg-white/10 p-7 text-sm transition-opacity">
                  <PrismResponse :responseText="row.answer" :responseId="index" />
                </div>
              </template>
            </div>
            <div class="flex flex-col gap-2">
              <div class="flex items-center justify-start gap-5">
                <KeyboardHint shortcut="/" label="Focus Input" />
                <KeyboardHint shortcut="⇧ ↵" label="New Line" />
                <KeyboardHint shortcut="Cmd/Ctrl + ↓" label="Scroll" />
                <KeyboardHint shortcut="↵" label="Send Message" />
                <div class="ml-auto flex gap-2">
                  <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :class="working && 'cursor-not-allowed !bg-[#888888]'" :disabled="working" :toggled="false">
                    <template #icon>
                      <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                        <path d="M21 2L20 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M3 2L4 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M21 16L20 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M3 16L4 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9 18H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10 21H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M11.9998 3C7.9997 3 5.95186 4.95029 5.99985 8C6.02324 9.48689 6.4997 10.5 7.49985 11.5C8.5 12.5 9 13 8.99985 15H14.9998C15 13.0001 15.5 12.5 16.4997 11.5001L16.4998 11.5C17.4997 10.5 17.9765 9.48689 17.9998 8C18.0478 4.95029 16 3 11.9998 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg>
                    </template>
                    <span>Ideas</span>
                  </PrimaryButton>
                  <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :class="working && 'cursor-not-allowed !bg-[#888888]'" :disabled="working" :toggled="false">
                    <template #icon>
                      <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M9 19L3.78974 20.7368C3.40122 20.8663 3 20.5771 3 20.1675L3 5.43246C3 5.1742 3.16526 4.94491 3.41026 4.86325L9 3M9 19L15 21M9 19L9 3M15 21L20.5897 19.1368C20.8347 19.0551 21 18.8258 21 18.5675L21 3.83246C21 3.42292 20.5988 3.13374 20.2103 3.26325L15 5M15 21L15 5M15 5L9 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </template>
                    <span>Maps</span>
                  </PrimaryButton>
                  <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :class="working && 'cursor-not-allowed !bg-[#888888]'" :disabled="working" :toggled="false">
                    <template #icon>
                      <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                        <path d="M3.33789 17C5.06694 19.989 8.29866 22 12.0001 22C15.7015 22 18.9332 19.989 20.6622 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M3.33789 7C5.06694 4.01099 8.29866 2 12.0001 2C15.7015 2 18.9332 4.01099 20.6622 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M13 21.9506C13 21.9506 14.4079 20.0966 15.2947 16.9999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M13 2.04932C13 2.04932 14.4079 3.90328 15.2947 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M11 21.9506C11 21.9506 9.59215 20.0966 8.70532 16.9999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M11 2.04932C11 2.04932 9.59215 3.90328 8.70532 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9 10L10.5 15L12 10L13.5 15L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M1 10L2.5 15L4 10L5.5 15L7 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17 10L18.5 15L20 10L21.5 15L23 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg>
                    </template>
                    <span>Search</span>
                  </PrimaryButton>
                  <PrimaryButton color="#1a1a1a" opacity="100" hoverOpacity="100" :class="working && 'cursor-not-allowed !bg-[#888888]'" :disabled="working" :toggled="false">
                    <template #icon>
                      <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M21.4383 11.6622L12.2483 20.8522C11.1225 21.9781 9.59552 22.6106 8.00334 22.6106C6.41115 22.6106 4.88418 21.9781 3.75834 20.8522C2.63249 19.7264 2 18.1994 2 16.6072C2 15.015 2.63249 13.4881 3.75834 12.3622L12.9483 3.17222C13.6989 2.42166 14.7169 2 15.7783 2C16.8398 2 17.8578 2.42166 18.6083 3.17222C19.3589 3.92279 19.7806 4.94077 19.7806 6.00222C19.7806 7.06368 19.3589 8.08166 18.6083 8.83222L9.40834 18.0222C9.03306 18.3975 8.52406 18.6083 7.99334 18.6083C7.46261 18.6083 6.95362 18.3975 6.57834 18.0222C6.20306 17.6469 5.99222 17.138 5.99222 16.6072C5.99222 16.0765 6.20306 15.5675 6.57834 15.1922L15.0683 6.71222" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </template>
                    <span>Attach</span>
                  </PrimaryButton>
                </div>
              </div>
              <div class="relative flex w-full">
                <div v-if="working" class="absolute bottom-2 left-2 z-50 flex items-center gap-5 rounded-2xl bg-black/70 pl-5 pr-3 text-xs backdrop-blur-2xl ah-[36px]">
                  <span class="text-white/70">Milai is typing</span>
                  <TypingDots />
                </div>
                <AutoResizeTextarea ref="autoTextarea" v-model="prompt" placeholder="Ask anything..." @enter="!working && talk()" :disabled="working" :readonly="working" />
                <PrimaryButton @click="talk" color="#1a1a1a" opacity="100" hoverOpacity="100" :class="['absolute bottom-2 right-2 z-50', working && 'cursor-not-allowed !border-[#888] !bg-[#888]']" :disabled="working" :onlyIcon="true">
                  <template #icon>
                    <svg v-if="isPromptEmpty" width="24" height="24" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor" class="icons">
                      <rect x="9" y="2" width="6" height="12" rx="3" stroke="currentColor" stroke-width="2"></rect>
                      <path d="M5 10V11C5 14.866 8.13401 18 12 18V18V18C15.866 18 19 14.866 19 11V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M12 18V22M12 22H9M12 22H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg v-else width="24" class="icons" height="24" viewBox="0 0 24 24" stroke-width="2" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                      <path d="M10.5 9C10.5 9 10.5 7 9.5 5C13.5 5 16 7.49997 16 7.49997C16 7.49997 19.5 7 22 12C21 17.5 16 18 16 18L12 20.5C12 20.5 12 19.5 12 17.5C9.5 16.5 6.99998 14 7 12.5C7.00001 11 10.5 9 10.5 9ZM10.5 9C10.5 9 11.5 8.5 12.5 8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M2 9.5L3 12.5L2 15.5C2 15.5 7 15.5 7 12.5C7 9.5 2 9.5 2 9.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M17 12.01L17.01 11.9989" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                </PrimaryButton>
              </div>
            </div>
          </div>
        </transition>
      </template>
    </TransitionGroup>
    <teleport to="body">
      <transition name="fade">
        <div v-if="showHistoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-white/5 backdrop-blur-2xl will-change-auto">
          <div class="flex w-full max-w-md flex-col gap-3 rounded-2xl bg-black p-5 text-white">
            <h2 class="mx-auto w-full text-center text-sm">Chat History</h2>
            <div class="uncommon-class my-3 flex max-h-[600px] w-full flex-col gap-2 overflow-y-auto">
              <div v-for="item in chats" :key="item.id" class="flex w-full cursor-pointer items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm transition-colors hover:bg-white/10" @click="selectChat(item)">
                <span>{{ item.name }}</span>
                <PrimaryButton @click="deleteChat(item.id)" color="#1a1a1a" opacity="100" hoverOpacity="100" :onlyIcon="true">
                  <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                      <polyline data-v-ab38e5bd="" points="3 6 5 6 21 6"></polyline>
                      <path data-v-ab38e5bd="" d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                      <line data-v-ab38e5bd="" x1="10" y1="11" x2="10" y2="17"></line>
                      <line data-v-ab38e5bd="" x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                  </template>
                </PrimaryButton>
              </div>
            </div>
            <div class="flex justify-center">
              <PrimaryButton @click="showHistoryModal = false" color="#1a1a1a" opacity="100" hoverOpacity="100">
                <span>Close</span>
              </PrimaryButton>
            </div>
          </div>
        </div>
      </transition>
    </teleport>
    <!-- <div>
      <PrimaryButton @click="toggleWebSearch">Web Search</PrimaryButton>
      <PrimaryButton @click="toggleMaps">Maps</PrimaryButton>
      <div v-if="webSearchEnabled">Web Search Content</div>
      <div v-if="mapsEnabled">Maps Content</div>
    </div> -->
    <div>
      <PrimaryButton @click="startTextToSpeech">Enable Text-to-Speech</PrimaryButton>
      <!-- <PrimaryButton @click="stopTextToSpeech">Disable Text-to-Speech</PrimaryButton> -->
      <div v-for="message in chatMessages" :key="message.id">
        <p>{{ message.text }}</p>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped lang="pcss">
.common-class {
  scrollbar-gutter: 50px;
}

.common-class::-webkit-scrollbar {
  width: 20px;
}

.common-class::-webkit-scrollbar-track {
  background: #000;
  background-color: #000;
  border-radius: 13px;
}

.common-class::-webkit-scrollbar-thumb {
  background: #333;
  background-color: #333;
  border-left: 8px solid #000;
  border-right: 8px solid #000;
  border-radius: 5px;
}

.common-class::-webkit-scrollbar-thumb:hover {
  background-color: #555;
  cursor: pointer;
}

.uncommon-class {
  scrollbar-gutter: 50px;
}

.uncommon-class::-webkit-scrollbar {
  width: 20px;
}

.uncommon-class::-webkit-scrollbar-track {
  background: #000;
  background-color: #000;
  border-radius: 8px;
}

.uncommon-class::-webkit-scrollbar-thumb {
  background: #333;
  background-color: #333;
  border-left: 8px solid #000;
  border-right: 8px solid #000;
  border-radius: 8px;
}

.uncommon-class::-webkit-scrollbar-thumb:hover {
  background-color: #555;
  cursor: pointer;
}

:deep(.answer pre) {
  scrollbar-gutter: 50px;
}

:deep(.answer pre)::-webkit-scrollbar {
  width: 20px;
  height: 20px;
}

:deep(.answer pre)::-webkit-scrollbar-track {
  background: #2d2d2d;
  background-color: #2d2d2d;
  border-radius: 13px;
}

:deep(.answer pre)::-webkit-scrollbar-thumb {
  background: #555;
  background-color: #555;
  border: 8px solid #2d2d2d;
  border-radius: 13px;
}

:deep(.answer pre)::-webkit-scrollbar-thumb:hover {
  background-color: #888;
  cursor: pointer;
}

:deep(.answer pre),
:deep(.answer table),
:deep(.answer ol),
:deep(.answer ul),
:deep(.answer p) {
  @apply mb-3 block last:mb-0;
}

:deep(.answer pre) {
  @apply relative rounded-lg;
}

:deep(.answer p strong) {
  @apply mb-1 mt-3;
}

:deep(.answer table) {
  @apply table w-full table-auto border-collapse border border-white/20 text-left;
}

:deep(.answer table th),
:deep(.answer table td) {
  @apply border border-white/20 p-2;
}

:deep(.answer ol),
:deep(.answer ul) {
  @apply list-disc pl-5;
}

:deep(.answer hr) {
  @apply my-3 border-white/20;
}

:deep(.answer a) {
  @apply text-accent/90 transition-colors hover:text-accent hover:underline;
}

.expand-enter-active,
.expand-leave-active {
  transition:
    opacity 0.3s ease-out,
    max-height 0.3s ease-out,
    transform 0.3s ease-out;
}

.expand-enter-from,
.expand-leave-to {
  max-height: 0;
  transform-origin: center;
  transform: scale(0);
  opacity: 0;
}

.fade-mask {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 200px;
  pointer-events: none;
  background: linear-gradient(to bottom, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
  z-index: 30;
}
</style>

