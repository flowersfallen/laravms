<template>
  <div>
    <video ref="videoPlayer" class="video-js" />
  </div>
</template>

<script>
import videojs from 'video.js'
import zhCN from 'video.js/dist/lang/zh-CN.json'

export default {
  name: 'VideoPlayer',
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
    var that = this
    videojs.addLanguage('zh-CN', zhCN)
    this.player = videojs(this.$refs.videoPlayer, this.options, () => {
      this.player.log('onPlayerReady', this)
    })
    const myButton1 = this.player.getChild('ControlBar').addChild('button', { // eslint-disable-line no-unused-vars
      controlText: '入点',
      className: 'vjs-visible-text',
      clickHandler: function(event) {
        this.player_.pause()
        that.$emit('syncPoint', 0, Math.floor(this.player_.currentTime()))
      }
    })
    const myButton2 = this.player.getChild('ControlBar').addChild('button', { // eslint-disable-line no-unused-vars
      controlText: '出点',
      className: 'vjs-visible-text',
      clickHandler: function(event) {
        this.player_.pause()
        that.$emit('syncPoint', 1, Math.floor(this.player_.currentTime()))
      }
    })
  },
  beforeDestroy() {
    if (this.player) {
      this.player.dispose()
    }
  }
}
</script>
