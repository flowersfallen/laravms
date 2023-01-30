<template>
  <div class="app-container">
    <el-input v-model="searchValue" placeholder="关键词，多个用空格隔开" style="margin-bottom:30px;" clearable @change="searchValueChange" />
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
    >
      <el-table-column label="ID" width="95" align="center">
        <template slot-scope="scope">
          {{ scope.$index + 1 }}
        </template>
      </el-table-column>
      <el-table-column label="缩略图" width="110" align="center">
        <template slot-scope="scope">
          <img style="width:80px;height:40px;" :src="scope.row.thumb" @click="handleUpdate(scope.row)">
        </template>
      </el-table-column>
      <el-table-column label="标题">
        <template slot-scope="scope">
          <span @click="handleUpdate(scope.row)">{{ scope.row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="标签">
        <template slot-scope="scope">
          {{ scope.row.tags }}
        </template>
      </el-table-column>
      <el-table-column label="时长" width="110" align="center">
        <template slot-scope="scope">
          {{ scope.row.duration }}
        </template>
      </el-table-column>
      <el-table-column label="宽高" width="110" align="center">
        <template slot-scope="scope">
          {{ scope.row.width }} * {{ scope.row.height }}
        </template>
      </el-table-column>
      <el-table-column label="创建人" width="110" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.admin_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="创建时间" width="200" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="fetchData" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <video-origin ref="videoOrigin" :options="videoOptions" />
    </el-dialog>

  </div>
</template>

<script>
import { listClip } from '@/api/video'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import VideoOrigin from '@/components/VideoOrigin.vue'
import 'video.js/dist/video-js.css'

export default {
  components: { Pagination, VideoOrigin },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'gray',
        deleted: 'danger'
      }
      return statusMap[status]
    }
  },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: true,
      searchValue: '',
      listQuery: {
        page: 1,
        limit: 10
      },
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
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const data = {
        keyword: this.searchValue,
        page: this.listQuery.page,
        pageSize: this.listQuery.limit,
        share: 1
      }
      listClip(data).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.listLoading = false
      })
    },
    searchValueChange() {
      this.fetchData()
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
<style>
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
