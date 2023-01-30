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
          <img style="width:80px;height:40px;" :src="scope.row.thumb" @click="clipVideo(scope.row)">
        </template>
      </el-table-column>
      <el-table-column label="标题">
        <template slot-scope="scope">
          {{ scope.row.title }}
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
  </div>
</template>

<script>
import { listVideo } from '@/api/video'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  components: { Pagination },
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
      listVideo(data).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.listLoading = false
      })
    },
    searchValueChange() {
      this.fetchData()
    },
    clipVideo(row) {
      this.$router.push({
        name: 'ClipVideo',
        params: {
          url: row.video,
          title: row.title,
          upload_id: row.id
        }
      })
    }
  }
}
</script>
