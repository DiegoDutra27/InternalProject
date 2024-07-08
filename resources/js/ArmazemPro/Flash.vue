<template>
  <div id="flashmsg">
  </div>
</template>

<script>

export default {
  props: {
    timeout: {
      type: Number,
      default: 2500
    }
  },
  data() {
    return {
      isVisible: true
    }
  },
  mounted() {
      if (this.$page.props.flash.message) {
        if (typeof this.$page.props.flash.message === 'string') {

          let msg = this.$page.props.flash.message.split('|');
          
          let instance = this.$toast.open({
            message: msg[0],
            type: msg[1] || 'success',
            duration: msg[2] || this.timeout,
            position: msg[3] || 'top-right'
          });
        }
      }
  }
}

</script>
