<template>
  <uploader :options="options" class="uploader-example" @file-success="fileSuccess">
    <uploader-unsupport />
    <uploader-drop>
      <uploader-btn :attrs="attrs">选择视频</uploader-btn>
    </uploader-drop>
    <uploader-list />
  </uploader>
</template>

<script>
import router from '@/router'

export default {
  data() {
    return {
      options: {
        target: '/admin/video/uploadVideo',
        testChunks: false,
        query: { token: window.sessionStorage.getItem('token') }
      },
      attrs: {
        accept: 'video/*'
      }
    }
  },
  methods: {
    fileSuccess() {
      var message = arguments[2]
      var obj = JSON.parse(message)
      if (obj.code === 50012) {
        router.push('/login')
      }
    }
  }
}
</script>

<style>
  .uploader-example {
    width: 880px;
    padding: 15px;
    margin: 40px auto 0;
    font-size: 12px;
    box-shadow: 0 0 10px rgba(0, 0, 0, .4);
  }
  .uploader-example .uploader-btn {
    margin-right: 4px;
  }
  .uploader-example .uploader-list {
    max-height: 440px;
    overflow: auto;
    overflow-x: hidden;
    overflow-y: auto;
  }
</style>
