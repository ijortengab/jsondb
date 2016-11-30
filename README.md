# Json as your Database

PHP Class

Inspire from MongoDB

Your Directory as your collection
Your File as your document

Example:


```
$json = Document::load('data.json');
$json->field_created = time();
$json->save();
```


```
$jsonDirectory = Collection::load('/home/ijortengab/json');
foreach ($jsonDirectory->each as $doc) {
    $doc->field_updated = time();
    $doc->save();
}

```