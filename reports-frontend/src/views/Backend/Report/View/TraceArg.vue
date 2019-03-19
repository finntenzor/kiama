<template>
  <span class="backend-report-view-trace-arg" v-if="compType === 'object'">
    <span>object(</span>
    <span class="backend-report-view-trace-arg class" :title="value">{{ value | last }}</span>
    <span>)</span>
  </span>
  <span class="backend-report-view-trace-arg" v-else-if="compType === 'array'">
    <span v-if="arrayBoundary">[</span>
    <template v-for="(item, i) in value">
      <span :key="2 * i" v-if="i != 0">, </span>
      <BackendReportViewTraceArg :key="2 * i + 1" :type="item.type" :value="item.value" />
    </template>
    <span v-if="arrayBoundary">]</span>
  </span>
  <span class="backend-report-view-trace-arg" v-else-if="compType === 'map'">
    <span v-if="arrayBoundary">[</span>
    <template v-for="(item, key, i) in value">
      <span :key="4 * i" v-if="i != 0">, </span>
      <span :key="4 * i + 1" class="backend-report-view-trace-arg string" :title="key">'{{ key }}'</span>
      <span :key="4 * i + 2"> => </span>
      <BackendReportViewTraceArg :key="4 * i + 3" :type="item.type" :value="item.value" />
    </template>
    <span v-if="arrayBoundary">]</span>
  </span>
  <span class="backend-report-view-trace-arg string" v-else-if="compType === 'string'" :title="value">'{{ compString }}'</span>
  <span class="backend-report-view-trace-arg number" v-else-if="compType === 'number'">{{ value }}</span>
  <span class="backend-report-view-trace-arg null" v-else-if="compType === 'null'">null</span>
  <span class="backend-report-view-trace-arg boolean" v-else-if="compType === 'boolean'">{{ value }}</span>
  <span class="backend-report-view-trace-arg resource" v-else-if="compType === 'resource'">resource</span>
  <span class="backend-report-view-trace-arg raw" v-else-if="compType === 'raw'">{{ value }}</span>
  <span class="backend-report-view-trace-arg invalid" v-else>&lt;Invalid Param({{ type }})&gt;</span>
</template>

<script>
import { last } from './filters'

export default {
  name: 'BackendReportViewTraceArg',
  filters: {
    last
  },
  props: {
    type: {
      type: String,
      required: true
    },
    value: {
      validator: () => true,
      required: true
    },
    arrayBoundary: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    compType() {
      if (this.type === 'array') {
        if (this.value instanceof Array) {
          return 'array'
        } else {
          return 'map'
        }
      } else {
        return this.type
      }
    },
    compString() {
      if (this.compType === 'string') {
        if (this.value.length > 20) {
          return this.value.substr(0, 20) + '...'
        } else {
          return this.value
        }
      } else {
        return null
      }
    }
  }
}
</script>

<style lang="scss">
.backend-report-view-trace-arg {
  &.class {
    color: #298eec;
    cursor: help;
    &:hover {
      text-decoration: underline;
    }
  }
  &.string {
    color: #e05924;
    cursor: help;
    &:hover {
      text-decoration: underline;
    }
  }
  &.number {
    color: #3cc530;
  }
  &.null {
    color: #0609b9;
  }
  &.boolean {
    color: #0609b9;
  }
  &.invalid {
    color: red;
    font-weight: bold;
  }
}

</style>
