<template>
  <div class="backend-report-view-method-url">
    <mu-flex>
      <mu-chip :color="color">{{ upperMethod }}</mu-chip>
      <div class="backend-report-view-method-url-div">
        <span class="backend-report-view-method-url-short-url">{{ shortUrl }}</span>
      </div>
      <mu-icon @click="change" :value="dock ? 'keyboard_arrow_up' : 'keyboard_arrow_down'" />
    </mu-flex>
    <span v-if="dock">{{ url }}</span>
  </div>
</template>

<script>
const reg = /^(http(s)?:\/\/)?((\w|\.)+)(:\d+)?/g

export default {
  name: 'BackendReportViewMethodUrl',
  props: {
    method: {
      type: String,
      required: true
    },
    url: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      dock: false
    }
  },
  computed: {
    color() {
      const map = {
        'GET': 'blue400',
        'POST': 'lightGreen400',
        'PUT': 'amber400',
        'DELETE': 'red400',
      }
      return map[this.upperMethod] || 'grey400'
    },
    upperMethod() {
      return this.method.toUpperCase()
    },
    shortUrl() {
      return this.url.replace(reg, '')
    }
  },
  methods: {
    change() {
      this.dock = !this.dock
    }
  }
}
</script>

<style lang="scss">
.backend-report-view-method-url {
  padding: 12px;
  font-size: 1.2em;
  word-wrap: break-word;
}
.backend-report-view-method-url-div {
  align-self: stretch;
  flex-grow: 1;
  position: relative;
}
.backend-report-view-method-url-short-url {
  position: absolute;
  width: 100%;
  height: 100%;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}
</style>
