<template>
  <div>
    <video ref="videoOrigin" class="video-js" />
  </div>
</template>

<script>
import videojs from 'video.js'
import zhCN from 'video.js/dist/lang/zh-CN.json'

export default {
  name: 'VideoOrigin',
  props: {
    options: {
      type: Object,
      default() {
        return {}
      }
    }
  },
  data() {
    return {
      player: null
    }
  },
  mounted() {
    videojs.addLanguage('zh-CN', zhCN)
    this.player = videojs(this.$refs.videoOrigin, this.options, () => {
      this.player.log('onPlayerReady', this)
    })
  },
  beforeDestroy() {
    if (this.player) {
      this.player.dispose()
    }
  },
  methods: {
    openIt() {
      if (this.player) {
        this.player.reset()
        this.player.src(this.options.sources[0].src)
        this.player.load()
      }
    }
  }
}
</script>
