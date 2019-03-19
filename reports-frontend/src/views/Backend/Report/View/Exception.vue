<template>
  <div class="backend-report-view-exception">
    <mu-flex wrap="wrap">
      <div class="backend-report-view-exception-line-one">
        <span>[{{ code }}]&nbsp;</span>
        <span class="backend-report-view-exception-name" :title="name">{{ name | last }}</span>
        <span>&nbsp;in</span>
      </div>
      <div class="backend-report-view-exception-line-two">
        <a class="backend-report-view-exception-file" :href="file" :title="'#' + line + ' ' + file">{{ file | last }} line {{ line }}</a>
      </div>
    </mu-flex>
    <mu-divider />
    <p class="backend-report-view-exception-message">{{ message }}</p>
  </div>
</template>

<script>
import { last } from './filters'

export default {
  name: 'BackendReportViewException',
  filters: {
    last: last
  },
  props: {
    code: {
      type: Number,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    file: {
      type: String,
      required: true
    },
    line: {
      type: Number,
      required: true
    },
    message: {
      type: String,
      required: true
    }
  }
}
</script>

<style lang="scss">
.backend-report-view-exception {
  font-family: Consolas, "Liberation Mono", Courier, Verdana, "微软雅黑";
}
.backend-report-view-exception-line-one {
  margin-right: 12px;
  color: #1565c0;
  font-size: 1.2em;
}
.backend-report-view-exception-name {
  cursor: help;
  text-decoration: underline;
  text-decoration-style: dotted;
}
.backend-report-view-exception-file {
  font-size: 1.2em;
  color: #868686;
  cursor: pointer;
  &:hover {
    text-decoration: underline;
  }
}
.backend-report-view-exception-message {
  margin: 4px 0;
  font-size: 1.4em;
  word-break: break-all;
  white-space: pre-wrap;
}
</style>
