<template>
  <div>
    <method-url :method="info.method" :url="info.url" />
    <pre>{{ info.details.echo }}</pre>
    <Exception
      :code="info.details.code"
      :name="info.details.name"
      :file="info.details.file"
      :line="info.details.line"
      :message="info.details.message" />
    <mu-divider />
    <Source
      :error-line="info.details.line"
      :first="info.details.source.first"
      :source="info.details.source.source" />
    <Trace
      :trace="fullTrace" />
    <Table
      v-if="!isEmpty(info.details.data)"
      title="Exception Data"
      :table="info.details.data" />
    <Table
      title="Environment Variables"
      :table="info.details.tables" />
    <mu-divider />
    <p>ThinkPHP V{{ info.details.think_version }}</p>
  </div>
</template>

<script>
import MethodUrl from './MethodUrl'
import Exception from './Exception'
import Source from './Source'
import Trace from './Trace'
import Table from './Table'

export default {
  name: 'BackendReportView',
  components: {
    MethodUrl,
    Exception,
    Source,
    Trace,
    Table
  },
  props: {
    info: {
      type: Object,
      required: true
    }
  },
  computed: {
    fullTrace() {
      const details = this.info.details
      const copy = [ ...details.trace ]
      copy.unshift({
        file: details.file,
        line: details.line
      })
      return copy
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

<style>

</style>
