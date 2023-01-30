<template>
  <div>
    <el-row :gutter="40" class="panel-group">
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              上传数
            </div>
            <count-to :start-val="0" :end-val="props[0][0]" :duration="3000" class="card-panel-num" /> 个
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              切片数
            </div>
            <count-to :start-val="0" :end-val="props[0][1]" :duration="3000" class="card-panel-num" /> 个
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              合并数
            </div>
            <count-to :start-val="0" :end-val="props[0][2]" :duration="3000" class="card-panel-num" /> 个
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              总数
            </div>
            <count-to :start-val="0" :end-val="props[0][3]" :duration="3000" class="card-panel-num" /> 个
          </div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="40" class="panel-group">
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              上传时长 {{ props[1][0] | formatsec }}
            </div>
            <count-to :start-val="0" :end-val="props[1][0]" :duration="3000" class="card-panel-num" /> 秒
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              切片时长 {{ props[1][1] | formatsec }}
            </div>
            <count-to :start-val="0" :end-val="props[1][1]" :duration="3000" class="card-panel-num" /> 秒
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              合并时长 {{ props[1][2] | formatsec }}
            </div>
            <count-to :start-val="0" :end-val="props[1][2]" :duration="3000" class="card-panel-num" /> 秒
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              总时长 {{ props[1][3] | formatsec }}
            </div>
            <count-to :start-val="0" :end-val="props[1][3]" :duration="3000" class="card-panel-num" /> 秒
          </div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="40" class="panel-group">
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              上传大小 {{ props[2][0] | formatbytes }}
            </div>
            <count-to :start-val="0" :end-val="props[2][0]" :duration="3000" class="card-panel-num" /> B
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              切片大小 {{ props[2][1] | formatbytes }}
            </div>
            <count-to :start-val="0" :end-val="props[2][1]" :duration="3000" class="card-panel-num" /> B
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              合并大小 {{ props[2][2] | formatbytes }}
            </div>
            <count-to :start-val="0" :end-val="props[2][2]" :duration="3000" class="card-panel-num" /> B
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <div class="card-panel">
          <div class="card-panel-description">
            <div class="card-panel-text">
              总大小 {{ props[2][3] | formatbytes }}
            </div>
            <count-to :start-val="0" :end-val="props[2][3]" :duration="3000" class="card-panel-num" /> B
          </div>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import CountTo from 'vue-count-to'

export default {
  components: {
    CountTo
  },
  filters: {
    formatbytes: function(bytes) {
      if (bytes === 0) return '0 B'
      var k = 1000
      var sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
      var i = Math.floor(Math.log(bytes) / Math.log(k))

      return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i]
    },
    formatsec: function(value) {
      let total = parseInt(value);
      let hours = parseInt(total / 3600);
      let minutes = parseInt((total % 3600) / 60);
      let seconds = parseInt((total % 3600) % 60);
      let h = hours > 9 ? hours : '0' + hours;
      let m = minutes > 9 ? minutes : '0' + minutes;
      let s = seconds > 9 ? seconds : '0' + seconds;
      return h + ':' + m + ':' + s;
    }
  },
  props: {
    props: {
      type: Array,
      default() {
        return [
          [0, 0, 0, 0],
          [0, 0, 0, 0],
          [0, 0, 0, 0]
        ]
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.panel-group {
  margin-top: 18px;

  .card-panel-col {
    margin-bottom: 32px;
  }

  .card-panel {
    height: 108px;
    cursor: pointer;
    font-size: 12px;
    position: relative;
    overflow: hidden;
    color: #666;
    background: #fff;
    box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
    border-color: rgba(0, 0, 0, .05);

    &:hover {
      .card-panel-icon-wrapper {
        color: #fff;
      }

      .icon-people {
        background: #40c9c6;
      }

      .icon-message {
        background: #36a3f7;
      }

      .icon-money {
        background: #f4516c;
      }

      .icon-shopping {
        background: #34bfa3
      }
    }

    .icon-people {
      color: #40c9c6;
    }

    .icon-message {
      color: #36a3f7;
    }

    .icon-money {
      color: #f4516c;
    }

    .icon-shopping {
      color: #34bfa3
    }

    .card-panel-icon-wrapper {
      float: left;
      margin: 14px 0 0 14px;
      padding: 16px;
      transition: all 0.38s ease-out;
      border-radius: 6px;
    }

    .card-panel-icon {
      float: left;
      font-size: 48px;
    }

    .card-panel-description {
      font-weight: bold;
      margin: 26px;

      .card-panel-text {
        line-height: 18px;
        color: rgba(0, 0, 0, 0.45);
        font-size: 16px;
        margin-bottom: 12px;
      }

      .card-panel-num {
        font-size: 20px;
      }
    }
  }
}

@media (max-width:550px) {
  .card-panel-description {
    display: none;
  }

  .card-panel-icon-wrapper {
    float: none !important;
    width: 100%;
    height: 100%;
    margin: 0 !important;

    .svg-icon {
      display: block;
      margin: 14px auto !important;
      float: none !important;
    }
  }
}
</style>
