<template>
  <mu-list-item avatar button :ripple="false">
    <mu-list-item-content @click="watchReport">
      <mu-list-item-title>{{ title }}</mu-list-item-title>
      <mu-list-item-sub-title>{{ reportAt | parseTime }}</mu-list-item-sub-title>
    </mu-list-item-content>
    <mu-list-item-action>
      <mu-button icon @click="removeReport">
        <mu-icon value="delete"></mu-icon>
      </mu-button>
    </mu-list-item-action>
  </mu-list-item>
</template>

<script>
import { removeReportById } from '@/api/backend'
import { parseTime } from '@/util/index'

export default {
  name: 'BackendListItem',
  props: {
    id: {
      type: Number,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    reportAt: {
      type: Number,
      required: true
    }
  },
  filters: {
    parseTime(val) {
      return parseTime(val)
    },
  },
  methods: {
    watchReport() {
      this.$router.push({ name: 'BackendReport', params: { id: this.id }})
    },
    removeReport() {
      this.$confirm('确定删除该错误报告？', '提示', {
        type: 'warning'
      }).then(async({ result }) => {
        if (result) {
          this.$progress.start()
          const id = this.id
          try {
            const { data } = await removeReportById(id)
            this.info = data
          } catch (err) {
            this.$report(err)
          }
          this.$progress.done()
          this.$toast.message('已删除')
          this.$emit('refresh')
        }
      })
    }
  }
}
</script>

<style>

</style>
