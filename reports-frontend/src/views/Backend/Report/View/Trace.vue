<template>
  <div class="backend-report-view-trace">
    <h2 class="backend-report-view-trace-title">Call Stack</h2>
    <mu-divider />
    <ol class="backend-report-view-trace-stack">
      <trace-item
        v-for="(item, i) in trace"
        :key="i"
        :item="item"
      />
    </ol>
  </div>
</template>

<script>
import { last } from './filters'
import TraceItem from './TraceItem'

export default {
  name: 'BackendReportViewTrace',
  components: {
    TraceItem
  },
  filters: {
    last
  },
  props: {
    trace: {
      type: Array,
      required: true
    }
  }
}
</script>

<style lang="scss">
.backend-report-view-trace {
  font-family: Consolas, "Liberation Mono", Courier, Verdana, "微软雅黑";
}
.backend-report-view-trace-title {
  margin: 8px 0;
  color: #1565c0;
  font-size: 1.2em;
  font-weight: normal;
}
</style>
