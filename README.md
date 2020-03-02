#EN
# phpins
Prevents stupid mistakes, for example: forgot to specify connection for class

### Validators:  
IDefinedThisVariable - detected where variable is define  
INotForgottenAddUseThisClass - detected set "use" for all class in file  
INotUseCyrillicLetterC - detected cyrillic letter "c" in code  


### Use:
```
phpins -f {file-path}
```

### Example output:
```
RemoveDownLineIndex.php:
  WARNING:RemoveDownLineIndex.php:5:18:Class not use "FormData"
  WARNING:RemoveDownLineIndex.php:7:18:Class not use "EntityManagerInterface"
  WARNING:RemoveDownLineIndex.php:8:32:Class not use "Auth"
```

### Translations
[EN](https://github.com/ta-tikoma/phpins/blob/master/README.md)  
[RU](https://github.com/ta-tikoma/phpins/blob/master/README-RU.md)