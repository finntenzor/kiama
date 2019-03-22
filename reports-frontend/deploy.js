#!/usr/bin/env node
/**
 * payload.js
 * 将dist中的替换到后端项目中
 * @author 董江彬 <dongjiangbin@tiaozhan.com>
 */
// 依赖项
const fromDir = './dist'
const toDir = '../backend/public/static/reports'
const indexToDir = '../backend/application/dev/view/reports'
const fileutils = require('./fileutils')

// 导出主程序
function main() {
  fileutils.rm(toDir)
  fileutils.cp(fromDir, toDir)
  fileutils.mv(toDir + '/index.html', indexToDir + '/index.html')
  console.log('已成功将前端文件导出到后端项目中')
}
main()
