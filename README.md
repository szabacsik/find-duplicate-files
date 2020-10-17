# Find and delete duplicate files in linux

<https://rmlint.readthedocs.io/en/latest/index.html>
<https://github.com/sahib/rmlint>
```shell
rmlint --algorithm=sha256 --sort-by=M --rank-by=PDM -g /srv/private/Photos
```

<https://www.tecmint.com/fdupes-find-and-delete-duplicate-files-in-linux>
```shell
fdupes --recurse --reverse --summarize /srv/private/Photos
```
<https://www.tecmint.com/find-and-delete-duplicate-files-in-linux/>
```shell
rdfind -dryrun true -checksum sha1 -makeresultsfile false /srv/private/Photos
```

<https://unix.stackexchange.com/questions/277697/whats-the-quickest-way-to-find-duplicated-files>
```shell
find . ! -empty -type f -exec md5sum {} + | sort | uniq -w32 -dD
```

<https://www.tecmint.com/fslint-find-and-remove-duplicate-unwanted-files-in-linux/>
<http://write.flossmanuals.net/fslint/duplicates/>
```shell
/usr/share/fslint/fslint/findup -t --summary ~/test
```