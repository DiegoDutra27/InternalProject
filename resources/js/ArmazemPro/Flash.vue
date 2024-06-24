<template>
  <div id="flashmsg"
       v-bind="handleToast()">
  </div>
</template>

<script>
export default {
  props: {
    timeout: {
      type: Number,
      default: 5000
    }
  },
  data() {
    return {
      isVisible: true
    }
  },
  methods: {
    handleToast() {
      if (this.$page.props.flash.message) {
        if (typeof this.$page.props.flash.message === 'string') {

          let msg = this.$page.props.flash.message.split('|');
          this.$toast.open({
            message: msg[0],
            type: msg[1] || 'success',
            duration: msg[2] || this.timeout,
            position: msg[3] || 'top-right'
          });
        }
      }
    }
  }
}
</script>
