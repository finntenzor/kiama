<template>
  <li class="backend-report-view-trace-stack-item">
    <span v-if="item['function']">at </span>
    <span v-if="item['class']" class="backend-report-view-trace-stack-item-class" :title="item['class']">{{ item['class'] | last }}</span>
    <span v-if="item['type']">{{ item['type'] }}</span>
    <template v-if="item['function']">
      <span class="backend-report-view-trace-stack-item-function">{{ item['function'] }}</span>
      <span>(</span>
      <TraceArg type="array" :value="item.args" :array-boundary="false" />
      <span>)</span>
      <span>&nbsp;</span>
    </template>
    <template v-if="item['file'] && item['line']">
      <span>in </span>
      <a class="backend-report-view-trace-statck-item-file" :href="item['file']" :title="'#' + item['line'] + ' ' + item['file']">{{ item['file'] | last }} line {{ item['line'] }}</a>
    </template>
  </li>
</template>

<script>
import TraceArg from './TraceArg'
import { last } from './filters'

export default {
  name: 'BackendReportViewTraceItem',
  components: {
    TraceArg
  },
  filters: {
    last
  },
  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>

<style lang="scss">
.backend-report-view-trace-stack-item {
  word-break: break-all;
}
.backend-report-view-trace-stack-item-class {
  color: #792c6e;
  font-weight: bold;
  cursor: help;
  &:hover {
    text-decoration: underline;
  }
}
.backend-report-view-trace-stack-item-function {
  color: #bb1166;
}
.backend-report-view-trace-statck-item-file {
  color: #868686;
  cursor: pointer;
  &:hover {
    text-decoration: underline;
  }
}
</style>
