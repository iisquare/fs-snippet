# Tool

- [Google Drive](https://medium.com/@acpanjan/download-google-drive-files-using-wget-3c2c025a8b99)
```
wget --load-cookies /tmp/cookies.txt "https://docs.google.com/uc?export=download&confirm=$(wget --quiet --save-cookies /tmp/cookies.txt --keep-session-cookies --no-check-certificate 'https://docs.google.com/uc?export=download&id=FILEID' -O- | sed -rn 's/.*confirm=([0-9A-Za-z_]+).*/\1\n/p')&id=FILEID" -O FILENAME && rm -rf /tmp/cookies.txt
```

- webp
```
(function () {
  var images = document.getElementsByTagName('img');
  for (var i in images) {
    var image = images[i];
    if (!image.src) continue;
    console.log(image.src)
    var index = image.src.indexOf('?x-oss-process');
    if (index !== -1) {
      image.src = image.src.substring(0, index);
      continue;
    }
  }
})()
```


## 在线工具

### 网路调试

- [网站访问速度测试](http://www.17ce.com/)
