<template>
  <div class="app-container">
    <div class="dndList">
      <div :style="{width:width1}" class="dndList-list">
        <h3>{{ list1Title }}</h3>
        <draggable :set-data="setData" :list="list1" group="article" class="dragArea">
          <div v-for="element in list1" :key="element.id" class="list-complete-item">
            <div class="list-complete-item-handle">
              <span @click="handleUpdate(element)">{{ element.title }}</span>
            </div>
            <div style="position:absolute;right:0px;">
              <span style="float: right ;margin-top: -20px;margin-right:5px;" @click="deleteEle(element)">
                <i style="color:#ff4949" class="el-icon-delete" />
              </span>
            </div>
          </div>
        </draggable>
        <div v-if="list1.length >= 2">
          <el-input v-model="combineTitle" placeholder="标题" />
          <div style="margin-top:10px;text-align:right;">
            <el-button @click="combineClip()">合并</el-button>
          </div>
        </div>
      </div>
      <div :style="{width:width2}" class="dndList-list">
        <h3>{{ list2Title }}</h3>
        <el-input v-model="searchValue" placeholder="关键词，多个用空格隔开" clearable @change="searchValueChange" />
        <draggable v-loading="listLoading" :list="list2" group="article" class="dragArea">
          <div v-for="element in list2" :key="element.id" class="list-complete-item">
            <div class="list-complete-item-handle2">
              <span @click="handleUpdate(element)">{{ element.title }}</span>
            </div>
            <div style="position:absolute;right:0px;">
              <span style="float: right ;margin-top: -20px;margin-right:5px;" @click="pushEle(element)">
                <i style="color:#ff4949" class="el-icon-plus" />
              </span>
            </div>
          </div>
        </draggable>
        <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getData" />
      </div>
      <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
        <video-origin ref="videoOrigin" :options="videoOptions" />
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { listClip, combineClip } from '@/api/video'
import draggable from 'vuedraggable'
import VideoOrigin from '@/components/VideoOrigin.vue'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import 'video.js/dist/video-js.css'

export default {
  components: { draggable, VideoOrigin, Pagination },
  data() {
    return {
      listLoading: true,
      list1: [],
      list2: [],
      list1Title: '选中列表',
      list2Title: '切片列表',
      width1: '48%',
      width2: '48%',
      combineTitle: '',
      temp: {
        id: undefined,
        title: '',
        video: ''
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '预览',
        create: 'Create'
      },
      videoOptions: {
        autoplay: false,
        controls: true,
        sources: [
          {
            src: ''
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
      total: 0,
      searchValue: '',
      listQuery: {
        page: 1,
        limit: 10
      }
    }
  },
  created() {
    this.getData()
  },
  methods: {
    getData() {
      this.listLoading = true
      const data = {
        keyword: this.searchValue,
        page: this.listQuery.page,
        pageSize: this.listQuery.limit
      }
      listClip(data).then(response => {
        this.total = response.data.total
        this.list2 = response.data.items
        this.listLoading = false
      })
    },
    searchValueChange() {
      this.getData()
    },
    isNotInList1(v) {
      return this.list1.every(k => v.id !== k.id)
    },
    isNotInList2(v) {
      return this.list2.every(k => v.id !== k.id)
    },
    deleteEle(ele) {
      for (const item of this.list1) {
        if (item.id === ele.id) {
          const index = this.list1.indexOf(item)
          this.list1.splice(index, 1)
          break
        }
      }
    },
    pushEle(ele) {
      if (this.isNotInList1(ele)) {
        this.list1.push(ele)
      }
    },
    setData(dataTransfer) {
      // to avoid Firefox bug
      // Detail see : https://github.com/RubaXa/Sortable/issues/1012
      dataTransfer.setData('Text', '')
    },
    combineClip() {
      var ids = []
      for (var i = 0; i < this.list1.length; i++) {
        ids.push(this.list1[i]['id'])
      }
      const data = {
        title: this.combineTitle,
        ids: ids
      }
      combineClip(data).then(res => {
        if (res.state) {
          this.$message.success('请求成功')
        } else {
          this.$message.error('请求失败')
        }
      }).catch(error => {
        console.log(error)
      })
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.videoOptions.sources[0].src = row.video
      this.$nextTick(() => {
        this.$refs.videoOrigin.openIt()
      })
    }
  }
}
</script>
<style lang="scss" scoped>
.dndList {
  background: #fff;
  padding-bottom: 40px;
  &:after {
    content: "";
    display: table;
    clear: both;
  }
  .dndList-list {
    float: left;
    padding-bottom: 30px;
    &:first-of-type {
      margin-right: 2%;
    }
    .dragArea {
      margin-top: 15px;
      min-height: 50px;
      padding-bottom: 30px;
    }
  }
}

.list-complete-item {
  cursor: pointer;
  position: relative;
  font-size: 14px;
  padding: 5px 12px;
  margin-top: 4px;
  border: 1px solid #bfcbd9;
  transition: all 1s;
}

.list-complete-item-handle {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  margin-right: 50px;
}

.list-complete-item-handle2 {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  margin-right: 20px;
}

.list-complete-item.sortable-chosen {
  background: #4AB7BD;
}

.list-complete-item.sortable-ghost {
  background: #30B08F;
}

.list-complete-enter,
.list-complete-leave-active {
  opacity: 0;
}

.video-js {
  width: 100%;
  height: 400px;
}
.vjs-has-started.vjs-user-inactive.vjs-playing .vjs-control-bar {
  opacity: 1;
}
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

