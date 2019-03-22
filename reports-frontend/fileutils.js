const fs = require('fs')
const path = require('path')
const join = path.join

/**
 * List the directories and files in the directory you assign.
 * 列出你指定目录的所有子目录和文件
 * @param {String} root the directory you want to list 你指定的目录
 */
function ls(root) {
  const children = fs.readdirSync(root)
  const directories = []
  const files = []
  for (let i = 0; i < children.length; i++) {
    const childName = children[i]
    const childStat = fs.statSync(join(root, childName))
    if (childStat.isFile()) {
      files.push(childName)
    } else if (childStat.isDirectory()) {
      directories.push(childName)
    }
  }
  return { directories, files }
}

class Walker {
  constructor(root, mode = 1) {
    this.root = root
    this.mode = mode
  }

  /**
   * rls = relative ls
   * 相对ls
   * @param {String} relative relative path 相对路径
   */
  rls(relative) {
    const result = ls(join(this.root, relative))
    return {
      relative,
      ...result
    }
  }

  /**
   * relative walk
   * 相对walk
   * @param {String} relative relative path 相对path
   */
  *rwalk(relative) {
    const result = this.rls(relative)
    if (this.mode === 1) {
      yield result
    }
    for (const directory of result.directories) {
      const relativePath = join(relative, directory)
      for (const result of this.rwalk(relativePath)) {
        yield result
      }
    }
    if (this.mode === 2) {
      yield this.rls(relative) // refresh directory status
    }
  }

  /**
   * walk with callback
   * 使用回调来遍历
   * @param {Function} callback 回调
   */
  walkWith(callback) {
    const rootStatus = fs.statSync(this.root)
    if (rootStatus.isFile()) {
      callback(true, '')
    } else {
      if (this.mode === 1) {
        callback(false, '')
      }
      for (const result of this.rwalk('.')) {
        for (const file of result.files) {
          callback(true, join(result.relative, file))
        }
        for (const directory of result.directories) {
          callback(false, join(result.relative, directory))
        }
      }
      if (this.mode === 2) {
        callback(false, '')
      }
    }
  }
}

/**
 * Walk in a directory like the python function os.walk
 * 类似于Python中walk的工作方式遍历目录
 * Left tree: files, Right tree: directories
 * 左子树定义为文件，右子树定义为目录
 * mode 1: inorder traversal(shallow files first)
 * 模式1为中序遍历（顶层文件先遍历）
 * mode 2: postorder traversal(deep files first)
 * 模式2位后序遍历（深层文件先遍历）
 * callback will be passed params isFile and relative path
 * 回调将传入两个参数，是否是文件和相对路径
 * @param {String} root the directory you want to list 你指定的目录
 * @param {Number} mode the walk mode 遍历模式
 * @param {Function} callback the walk callback 遍历回调，默认不使用
 */
function walk(root, mode = 1, callback = null) {
  const walker = new Walker(root, mode)
  if (callback === null) {
    return walker.rwalk('.')
  } else {
    return walker.walkWith(callback)
  }
}

/**
 * Remove a file or a directory
 * 删除一个文件或目录
 * @param {String} src the file or directory you want to remove 你要删的文件或目录
 */
function rm(src) {
  walk(src, 2, (isFile, relative) => {
    if (isFile) {
      fs.unlinkSync(join(src, relative))
    } else {
      fs.rmdirSync(join(src, relative))
    }
  })
}

/**
 * Copy file or directory
 * 复制文件或目录
 * @param {String} src srouce file or directory 源文件或目录
 * @param {String} dist distributional file or directory 目标文件或目录
 */
function cp(src, dist) {
  walk(src, 1, (isFile, relative) => {
    if (isFile) {
      fs.copyFileSync(join(src, relative), join(dist, relative))
    } else {
      fs.mkdirSync(join(dist, relative))
    }
  })
}

/**
 * Move file or directory
 * 移动文件或目录
 * @param {String} src srouce file or directory 源文件或目录
 * @param {String} dist distributional file or directory 目标文件或目录
 */
function mv(src, dist) {
  let i = 0
  for (;;) {
    try {
      fs.renameSync(src, dist)
      return
    } catch (e) {
      i++
      if (i >= 3) {
        throw e
      }
    }
  }
}

module.exports = {
  ls,
  rm,
  cp,
  mv,
  walk,
  Walker,
}
