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
      <el-table-column label="是否分享" width="110" align="center">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.switchValue" active-color="#0d5eff" @change="setShare(scope.row.id,scope.row.switchValue ? 1 :0)" />
        </template>
      </el-table-column>
      <el-table-column label="操作" width="110" align="center">
        <template slot-scope="scope">
          <span class="operate" @click="updateItem(scope.row)">编辑</span>|<span class="operate" @click="delItem(scope.row.id)">删除</span>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="fetchData" />

    <!--编辑弹窗-->
    <el-dialog
      :visible.sync="dialogVisible"
      title="修改"
      width="700px"
      @closed="closeDialog"
    >
      <el-form
        ref="addAccountForm"
        :model="addAccountForm"
        :rules="addAccountFormRules"
        status-icon
        label-width="100px"
      >
        <el-form-item label="标题:" prop="title">
          <el-input v-model="addAccountForm.title" />
        </el-form-item>
        <el-form-item label="标签" prop="tags">
          <el-input v-model="addAccountForm.tags" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">修改</el-button>
          <el-button @click="dialogVisible = false">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { deepClone } from '@/utils/index'
import { listVideo, setShare, delItem, updateVideo } from '@/api/video'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

const addAccountForm = {
  title: '',
  tags: ''
}

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
    const validateTitle = (rule, value, callback) => {
      if (!value) {
        callback(new Error('请输入标题'))
      } else {
        callback()
      }
    }
    const validateTag = (rule, value, callback) => {
      if (!value) {
        callback(new Error('请输入标签'))
      } else {
        callback()
      }
    }
    return {
      list: null,
      total: 0,
      listLoading: true,
      searchValue: '',
      listQuery: {
        page: 1,
        limit: 10
      },
      dialogVisible: false,
      addAccountForm: deepClone(addAccountForm),
      addAccountFormRules: {
        title: [{
          required: true,
          validator: validateTitle,
          trigger: 'blur'
        }],
        tags: [
          {
            required: true,
            validator: validateTag,
            trigger: 'blur'
          }
        ]
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
        pageSize: this.listQuery.limit
      }
      listVideo(data).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        $.each(this.list, function(i, item) {
          if (item.is_sharing === 1) {
            item.switchValue = true
          } else {
            item.switchValue = false
          }
        })
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
    },
    setShare(id, share) {
      const params = {
        id: id,
        is_sharing: share,
        type: 1
      }
      setShare(params).then(res => {
        if (res.state) {
          this.$message.success('请求成功')
          this.fetchData()
        } else {
          this.$message.error('请求失败')
        }
      }).catch(error => {
        console.log(error)
      })
    },
    delItem(id) {
      const params = {
        ids: id,
        type: 1
      }
      delItem(params).then(res => {
        if (res.state) {
          this.$message.success('请求成功')
          this.fetchData()
        } else {
          this.$message.error('请求失败')
        }
      }).catch(error => {
        console.log(error)
      })
    },
    updateItem(item) { // 打开修改用户弹窗
      // 创建修改参数
      Object.assign(this.addAccountForm, {
        id: ''
      })
      for (const key in this.addAccountForm) {
        this.addAccountForm[key] = item[key]
      }
      this.dialogVisible = true
    },
    onSubmit() { // 提交
      this.$refs['addAccountForm'].validate((valid) => {
        if (valid) {
          const params = {}
          for (const key in this.addAccountForm) {
            if ([null, undefined].indexOf(this.addAccountForm[key]) === -1) {
              params[key] = this.addAccountForm[key]
            }
          }
          params['type'] = 1
          updateVideo(params).then((res) => {
            if (res.state) {
              this.dialogVisible = false
              this.$message.success('修改成功')
              this.fetchData()
            }
          })
        } else {
          this.$message.error('表单信息填写错误！')
        }
      })
    },
    closeDialog() { // 关闭新增账号弹窗
      this.addAccountForm = deepClone(addAccountForm)
      this.$refs.addAccountForm.clearValidate()
    }
  }
}
</script>
<style>
.operate {
  color: #0a4bcc;
  cursor: pointer;
}
</style>
