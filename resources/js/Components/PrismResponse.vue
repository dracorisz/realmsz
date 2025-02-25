<script setup>
import { ref, watch, onMounted, nextTick } from "vue";

const props = defineProps({
  responseId: [String, Number],
  responseText: String,
});

const content = ref(null);
const formattedResponse = ref(props.responseText);

const highlightCode = async () => {
  await nextTick();
  if (content.value) {
    try {
      new Prism.highlightAll(content.value);
    } catch (error) {
      console.error("Prism.js highlighting error:", error);
    }
  }
};

watch(
  () => props.responseText,
  (newText) => {
    formattedResponse.value = newText;
    highlightCode();
  },
);

onMounted(() => {
  highlightCode();
  Prism.plugins.autoloader.languages_path = "prismjs/components/";
  // `select-code-${props.responseId}`
  Prism.plugins.toolbar.registerButton('', function (env) {
    let button = document.createElement("button");
    button.innerHTML = "Copy";

    button.addEventListener("click", function () {
      if (document.body.createTextRange) {
        let range = document.body.createTextRange();
        range.moveToElementText(env.element);
        range.select();
      } else if (window.getSelection) {
        let selection = window.getSelection();
        let range = document.createRange();
        range.selectNodeContents(env.element);
        selection.removeAllRanges();
        selection.addRange(range);
      }

      document.execCommand("copy");

      if (window.getSelection) window.getSelection().removeAllRanges();
      else if (document.selection) document.selection.empty();

      button.innerHTML = "Copied";

      setTimeout(() => {
        button.innerHTML = "Copy";
      }, 2000);
    });
    return button;
  });
});
</script>

<template>
  <span v-html="formattedResponse" ref="content"></span>
</template>

<style scoped lang="pcss">
:deep(div.code-toolbar > .toolbar) {
  @apply right-1 top-1 flex items-center gap-1;
}

:deep(div.code-toolbar > .toolbar > .toolbar-item) {
  @apply rounded-2xl bg-[#1c1c1c] transition-colors first:cursor-not-allowed last:hover:bg-primary;
}

:deep(div.code-toolbar > .toolbar > .toolbar-item > a),
:deep(div.code-toolbar > .toolbar > .toolbar-item > button),
:deep(div.code-toolbar > .toolbar > .toolbar-item > span) {
  @apply flex items-center justify-center bg-transparent px-3 py-1 text-xs text-white shadow-none ah-[24px];
}
</style>
