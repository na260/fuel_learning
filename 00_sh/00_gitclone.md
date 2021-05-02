

```
cd /home/site/site1/prj01 \
&& git init
```
# リモートリポジトリを追加
```
git remote add origin https://github.com/na260/fuel_learning.git
```

# 最新のソースコードを、リモートブランチから追跡ブランチに持ってくる
```
git fetch -p
```

# 1回目のブランチ確認
```
git branch -a
```

# 追跡ブランチのソースコードで、ローカルブランチのソースコードを強制上書き
```
git reset --hard origin/main
```