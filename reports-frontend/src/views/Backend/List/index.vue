<template>
  <div class="backend-list" v-loading="loading">
    <!-- 报告列表 -->
    <mu-list textline="two-line">
      <mu-sub-header inset>Reports</mu-sub-header>
      <list-item
        v-for="item in reports"
        :key="item.id"
        :id="item.id"
        :title="item.title"
        :report-at="item.report_at"
        @refresh="refresh" />
    </mu-list>

    <!-- 分页器 -->
    <mu-flex justify-content="center">
      <mu-pagination
        :total="pagination.total"
        :current.sync="pagination.current"
        :page-size="pagination.size"
        :page-count="5"
        @change="changePage" />
    </mu-flex>
  </div>
</template>

<script>
import ListItem from './Item'
import { getReportAtPage } from '@/api/backend'

export default {
  name: 'BackendList',
  components: {
    ListItem
  },
  data() {
    return {
      loading: false,
      pagination: {
        total: 0,
        current: 1,
        size: 0
      },
      reports: []
    }
  },
  async created() {
    this.refresh()
  },
  methods: {
    // 切换到目标页
    async changePage(page) {
      this.pagination.current = page
      this.loading = true
      this.$router.push({ name: 'BackendList', query: { page }})
      try {
        const { data } = await getReportAtPage(page)
        this.pagination.total = data.total
        this.pagination.size = data.per_page
        this.reports = data.data
      } catch (err) {
        this.$report(err)
      }
      this.loading = false
    },
    refresh() {
      this.changePage(this.pagination.current)
    }
  }
}
</script>

<style>

</style>
