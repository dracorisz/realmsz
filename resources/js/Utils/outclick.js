export default {
  beforeMount(el, binding) {
    el.outclickEvent = function (event) {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event);
      }
    };
    document.addEventListener("click", el.outclickEvent);
  },
  unmounted(el) {
    document.removeEventListener("click", el.outclickEvent);
  },
};
