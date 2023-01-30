<template>
  <div class="container">
    <video-player ref="video-player" :options="videoOptions" @syncPoint="syncPoint" />
    <div style="padding-top: 20px;">
      <el-form>
        <el-form-item>
          <el-col :span="2">
            <el-input v-model="pointIn" placeholder="入点" />
          </el-col>
          <el-col :span="1" style="text-align: center;">
            -
          </el-col>
          <el-col :span="2">
            <el-input v-model="pointOut" placeholder="出点" />
          </el-col>
          <el-col :span="1" style="text-align: center;">
            -
          </el-col>
          <el-col :span="4">
            <el-input v-model="clipTags" placeholder="标签,多个用英文逗号分隔" />
          </el-col>
          <el-col :span="1" style="text-align: center;">
            -
          </el-col>
          <el-col :span="10">
            <el-input v-model="clipTitle" placeholder="标题" />
          </el-col>
          <el-col :span="1" style="text-align: center;">
            -
          </el-col>
          <el-col :span="2">
            <el-button style="width:100%;" @click="addClip()">拆条</el-button>
          </el-col>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { clipVideo } from '@/api/video'
import VideoPlayer from '@/components/VideoPlayer.vue'
import 'video.js/dist/video-js.css'

export default {
  components: {
    VideoPlayer
  },
  data() {
    return {
      videoOptions: {
        autoplay: false,
        controls: true,
        sources: [
          {
            src: this.$route.params.url
          }
        ],
        controlBar: {
          playToggle: false,
          volumePanel: {
            inline: false
          },
          remainingTimeDisplay: false,
          fullscreenToggle: false,
          pictureInPictureToggle: false
        }
      },
      pointIn: '',
      pointOut: '',
      clipTitle: this.$route.params.title,
      clipTags: this.$route.params.tags,
      uploadId: this.$route.params.upload_id
    }
  },
  methods: {
    syncPoint: function(key, value) {
      if (key === 0) {
        this.pointIn = value
      } else {
        this.pointOut = value
      }
    },
    addClip() {
      if (this.pointIn === '' || this.pointOut === '' || this.clipTitle === '') {
        this.$message.warning('参数错误')
        return
      }
      const data = {
        in: parseInt(this.pointIn),
        out: parseInt(this.pointOut),
        title: this.clipTitle,
        tags: this.clipTags,
        upload_id: this.uploadId
      }
      clipVideo(data).then(res => {
        if (res.state) {
          this.$message.success('请求成功')
        } else {
          this.$message.error('请求失败')
        }
      }).catch(error => {
        console.log(error)
      })
    }
  }
}
</script>
<style>
.container {
  padding: 20px;
}
.video-js {
  width: 100%;
  height: 400px;
}
.vjs-has-started.vjs-user-inactive.vjs-playing .vjs-control-bar {
  opacity: 1;
}
/*
.video-js .vjs-current-time, .vjs-no-flex .vjs-current-time {
  display: inline;
}
.vjs-time-divider {
  display: inline;
  text-align: center;
}
*/
.video-js .vjs-duration, .vjs-no-flex .vjs-duration {
  display: inline;
}
.video-js .vjs-time-control {
  padding:0;
}
.vjs-paused .vjs-big-play-button,
.vjs-paused.vjs-has-started .vjs-big-play-button {
    display: block;
}
.video-js .vjs-time-tooltip {
    visibility: visible;
}
.video-js .vjs-control.vjs-visible-text {
  width: 4em;
  padding: 0;
}
</style>
