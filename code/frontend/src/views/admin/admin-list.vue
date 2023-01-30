<template>
  <div class="admin-container">
    <div class="search">
      <el-input v-model="searchWord" placeholder="输入用户账号搜索" @keyup.enter.native="searchAccount">
        <el-button slot="append" @click="searchAccount">搜索</el-button>
      </el-input>
    </div>
    <div class="list">
      <el-table
        ref="multipleTable"
        v-loading="loading"
        :data="userList"
        tooltip-effect="dark"
        height="calc(100vh - 268px)"
        style="width: 100%;"
        @selection-change="selectionChange"
      >
        <el-table-column type="selection" width="55" />
        <el-table-column prop="name" label="用户名称" width="150" />
        <el-table-column prop="email" label="账号" width="200" />
        <el-table-column prop="created_at" label="创建时间" show-overflow-tooltip />
        <el-table-column width="100" label="操作" show-overflow-tooltip>
          <template slot-scope="scope">
            <el-button type="text" @click="updateUser(scope.row)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="optionsBtn-list-page">
      <!--批量操作-->
      <div class="piling">
        <el-checkbox
          v-model="checkedAll"
          :indeterminate="isIndeterminate"
          @change="checkedAllSwitch"
        >全选</el-checkbox>
        <div class="optionsBtn">
          <el-button type="primary" size="small" @click="openAddDialog">新增账号</el-button>
          <el-button type="danger" size="small" @click="delUser">删除账号</el-button>
        </div>
      </div>
      <!--分页-->
      <div class="list-page">
        <el-pagination
          :current-page="current_page"
          :page-sizes="[20, 40, 60, 100]"
          :page-size="per_page"
          :pager-count="5"
          :total="total"
          background
          layout="total, sizes, prev, pager, next, jumper"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </div>
    </div>

    <!--添加账号弹窗-->
    <el-dialog
      :visible.sync="dialogVisible"
      :title="addAccountForm.id ? '修改账号' : '新增账号'"
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
        <el-form-item label="用户名称:" prop="name">
          <el-input v-model="addAccountForm.name" />
        </el-form-item>
        <el-form-item label="用户账号" prop="email">
          <el-input v-model="addAccountForm.email" :disabled="Boolean(addAccountForm.id)" />
        </el-form-item>
        <div class="dialog-pwd-div clearfix">
          <el-col :span="11">
            <el-form-item label="用户密码" prop="password">
              <el-input v-model="addAccountForm.password" type="password" />
            </el-form-item>
          </el-col>
          <el-col :span="2">&nbsp;</el-col>
          <el-col :span="11">
            <el-form-item label="确认密码:" prop="repass">
              <el-input v-model="addAccountForm.repass" type="password" />
            </el-form-item>
          </el-col>
        </div>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">{{ addAccountForm.id ? '修改' : '立即创建' }}</el-button>
          <el-button @click="dialogVisible = false">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { deepClone } from '@/utils/index'
import { addUser, updateUser, delUser, getList } from '@/api/user'

const addAccountForm = {
  name: '',
  email: '',
  password: '',
  repass: ''
}

export default {
  data() {
    const validatePass = (rule, value, callback) => {
      if (this.addAccountForm.id && !value && !this.addAccountForm.repass) callback()

      if (value === '' || typeof value === 'undefined') {
        callback(new Error('请输入密码'))
      } else if (value.length < 6 || value.length > 18) {
        callback(new Error('密码长度为6到18位'))
      } else {
        if (this.addAccountForm.repass !== '') {
          this.$refs.addAccountForm.validateField('repass')
        }
        callback()
      }
    }
    const validateRepass = (rule, value, callback) => {
      if (this.addAccountForm.id && !value && !this.addAccountForm.password) callback()

      if (value === '' || typeof value === 'undefined') {
        callback(new Error('请再次输入密码'))
      } else if (value.length < 6 || value.length > 18) {
        callback(new Error('密码长度为6到18位'))
      } else if (value !== this.addAccountForm.password) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    const validateName = (rule, value, callback) => {
      if (!value) {
        callback(new Error('请输入客户名称'))
      } else {
        callback()
      }
    }
    const validateEmail = (rule, value, callback) => {
      if (!value) {
        callback(new Error('请输入账号'))
      } else if (!/^[-!#$%&'*+\/0-9=?A-Z^_a-z{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/.test(value)) {
        callback(new Error('请输入正确的邮箱'))
      } else {
        callback()
      }
    }
    return {
      current_page: 1,
      per_page: 20,
      total: 0,
      loading: false,
      isIndeterminate: false,
      searchWord: '',
      userList: [],
      checkedAll: false,
      dialogVisible: false,
      addAccountForm: deepClone(addAccountForm),
      addAccountFormRules: {
        name: [{
          required: true,
          validator: validateName,
          trigger: 'blur'
        }],
        email: [
          {
            required: true,
            validator: validateEmail,
            trigger: 'blur'
          }
        ],
        password: [
          {
            validator: validatePass,
            trigger: 'blur'
          }
        ],
        repass: [
          {
            validator: validateRepass,
            trigger: 'blur'
          }
        ]
      },
      selectionArr: [] // 已勾选的用户数组
    }
  },
  created() {
    this.getList()
  },
  methods: {
    updateUser(user) { // 打开修改用户弹窗
      // 创建修改参数
      Object.assign(this.addAccountForm, {
        id: ''
      })
      for (const key in this.addAccountForm) {
        this.addAccountForm[key] = user[key]
      }
      this.dialogVisible = true
    },
    openAddDialog() {
      this.dialogVisible = true
    },
    getSelectedIds() { // 获取选取的 ids
      if (!this.selectionArr.length) {
        this.$message({
          message: '请选择你要更改的用户',
          type: 'warning'
        })
        return false
      }
      const ids = []
      for (const x in this.selectionArr) {
        ids.push(this.selectionArr[x].id)
      }
      return ids.join(',')
    },
    onSubmit() { // 提交
      this.$refs['addAccountForm'].validate((valid) => {
        if (valid) {
          const handleUser = this.addAccountForm.id ? updateUser : addUser
          // 格式化，去掉无用值
          const params = {}
          for (const key in this.addAccountForm) {
            if ([null, undefined].indexOf(this.addAccountForm[key]) === -1) {
              params[key] = this.addAccountForm[key]
            }
          }
          // 处理修改过，又置空不修改的情况
          if (!params.password && !params.repass) {
            delete params.password
            delete params.repass
          }
          handleUser(params).then((res) => {
            if (res.state) {
              this.dialogVisible = false
              if (params.id) {
                this.$message.success('账号修改成功')
              } else {
                this.$message.success('新增账号成功')
              }
              this.getList()
            }
          })
        } else {
          this.$message.error('表单信息填写错误！')
        }
      })
    },
    checkedAllSwitch(val) { // 全选切换
      if (val) {
        this.$refs.multipleTable.toggleAllSelection()
      } else {
        this.$refs.multipleTable.clearSelection()
      }
      this.isIndeterminate = false
    },
    selectionChange(selection) { // 列表选择触发
      this.selectionArr = selection
      if (selection.length > 0 && selection.length < this.userList.length) {
        this.isIndeterminate = true
      } else {
        this.isIndeterminate = false
      }
      this.checkedAll = (selection.length === this.userList.length)
    },
    handleSizeChange(val) { // 每页条数修改
      this.per_page = val
      this.getList(1)
    },
    handleCurrentChange(val) { // 点击指定页码
      this.getList(val)
    },
    delUser() { // 删除用户
      if (!this.selectionArr.length) {
        this.$message({
          message: '请选择你要删除的用户',
          type: 'warning'
        })
        return false
      }
      this.$confirm(`是否删除已选用户?`, '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        const ids = this.getSelectedIds()
        delUser({
          ids
        }).then((res) => {
          if (res.state) {
            this.$message.success('删除用户成功')
            const pageTotal = Math.ceil(this.total / this.per_page)
            const currentTotal = Math.ceil((this.total - ids.length) / this.per_page)
            if (pageTotal !== currentTotal) {
              const currentPage = this.current_page - 1
              this.current_page = Math.max(1, currentPage)
            }
            this.getList()
          }
        })
      }).catch((err) => {
        console.log(err)
      })
    },
    closeDialog() { // 关闭新增账号弹窗
      this.addAccountForm = deepClone(addAccountForm)
      this.$refs.addAccountForm.clearValidate()
    },
    searchAccount() {
      this.getList(1)
    },
    getList(val) { // 获取数据列表
      const params = {
        page: val || this.current_page,
        pagesize: this.per_page,
        keyword: this.searchWord
      }
      this.loading = true
      getList(params).then((res) => {
        if (res.state) {
          this.current_page = res.data.current_page
          this.total = res.data.total
          this.userList = res.data.data
        }
        this.loading = false
      }).catch((err) => {
        console.log(err)
      })
    }
  }
}
</script>

<style scoped lang="scss">
.admin-container {
  .search {
    margin: 30px;
    text-align: right;
    border-bottom: 1px solid #ebeef5;
    padding-bottom: 25px;
    .el-input {
      width: 400px;
    }
  }
  .optionsBtn-list-page {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-left: 14px;
    .piling {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .optionsBtn {
      margin: 20px 0;
      margin-left: 10px;
    }
    .list-page {
      margin: 30px 0;
    }
  }
  .dialog-status-div .el-date-editor.el-input {
    width: 200px;
  }
}
</style>
