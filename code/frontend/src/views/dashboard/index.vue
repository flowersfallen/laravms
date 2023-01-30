<template>
  <div class="dashboard-container">
    <panel-group :props="props" />
  </div>
</template>

<script>
import PanelGroup from './components/PanelGroup'
import { getStat } from '@/api/user'

export default {
  name: 'Dashboard',
  components: { PanelGroup },
  data() {
    return {
      props: [
        [0, 0, 0, 0],
        [0, 0, 0, 0],
        [0, 0, 0, 0]
      ]
    }
  },
  created() {
    this.getData()
  },
  methods: {
    getData() {
      getStat().then(response => {
        this.props = response.data.items
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.dashboard {
  &-container {
    margin: 30px;
  }
  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}
</style>
