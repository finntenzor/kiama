<template>
  <div class="backend-report-view-table">
    <h2 class="backend-report-view-table-title">{{ title }}</h2>
    <mu-divider />
    <table v-for="(content, caption) in table" :key="caption" class="backend-report-view-table-table">
      <caption class="backend-report-view-table-caption">
        <span>{{ caption }}</span>
        <small v-if="isEmpty(content)" class="backend-report-view-table-small">empty</small>
      </caption>
      <tbody>
        <tr v-for="(value, key) in content" :key="key">
          <td class="backend-report-view-table-key">{{ key }}</td>
          <td class="backend-report-view-table-value">
            <pre>{{ value }}</pre>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'BackendReportViewTable',
  props: {
    title: {
      type: String,
      required: true
    },
    table: {
      validator(val) {
        return typeof val === 'object' || val instanceof Array
      },
      required: true
    }
  },
  methods: {
    isEmpty(target) {
      for (const key in target) {
        return false
      }
      return true
    }
  }
}
</script>

<style lang="scss">
.backend-report-view-table {
  font-family: Consolas, "Liberation Mono", Courier, Verdana, "微软雅黑";
}
.backend-report-view-table-title {
  margin: 8px 0;
  color: #1565c0;
  font-size: 1.2em;
  font-weight: normal;
}
.backend-report-view-table-table {
  width: 100%;
  margin: 12px 0;
  box-sizing: border-box;
  table-layout: fixed;
  word-wrap: break-word;
}
.backend-report-view-table-caption {
  text-align: left;
  font-size: 18px;
  font-weight: bolder;
  padding: 6px 0;
}
.backend-report-view-table-small {
  font-weight: normal;
  display: inline-block;
  margin-left: 10px;
  color: #ccc;
}
.backend-report-view-table-key {
  position: relative;
  font-weight: bold;
  font-size: 12px;
  padding: 0 6px;
  vertical-align: top;
  word-break: break-all;
  &::before {
    position: absolute;
    left: -0.4em;
    content: '*';
    font-weight: normal;
  }
}
.backend-report-view-table-value {
  font-family: Consolas, "Liberation Mono", Courier, "微软雅黑";
}
</style>
