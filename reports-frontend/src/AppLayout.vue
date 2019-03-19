<template>
  <mu-flex class="app-layout" direction="column" align-items="stretch">
    <mu-appbar color="primary" class="app-appbar">
      <mu-button icon slot="left" @click="$router.push('/')">
        <mu-icon value="home"></mu-icon>
      </mu-button>
      <mu-button icon slot="left" @click="$router.go(-1)">
        <mu-icon value="arrow_back"></mu-icon>
      </mu-button>
      <mu-button icon slot="left" @click="$router.go(1)">
        <mu-icon value="arrow_forward"></mu-icon>
      </mu-button>
      <span>错误报告</span>
    </mu-appbar>
    <mu-flex fill class="app-layout-main-outter">
      <div class="app-layout-main">
        <slot></slot>
      </div>
    </mu-flex>
    <!-- <mu-bottom-nav :value.sync="shift" shift>
      <mu-bottom-nav-item value="backend" title="后端" icon="ondemand_video"></mu-bottom-nav-item>
      <mu-bottom-nav-item value="frontend" title="前端" icon="music_note"></mu-bottom-nav-item>
    </mu-bottom-nav> -->
  </mu-flex>
</template>

<script>
export default {
  name: 'AppLayout',
  computed: {
    shift: {
      get() {
        const path = this.$route.path
        return path.startsWith('/backend') ? 'backend' : 'frontend'
      },
      set(val) {
        if (val === 'backend') {
          this.$router.push('/backend')
        } else {
          this.$router.push('/frontend')
        }
      }
    }
  }
}
</script>

<style lang="scss">
.app-layout {
  height: 100%;
  overflow: hidden;
}
.mu-appbar.app-appbar {
  align-self: stretch;
}
// 绝对定位和相对定位配合来占满剩余空间
.app-layout-main-outter {
  position: relative;
}
.app-layout-main {
  position: absolute;
  height: 100%;
  width: 100%;
  overflow-y: scroll;
}
</style>
