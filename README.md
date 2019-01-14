# hs_muutokset
[@hs_muutokset](https://twitter.com/hs_muutokset/) Twitter-bot code

Loads news from www.hs.fi rss-feeds and compares the difference to previous title. If title change is significant, tweets that out.

fonts for creating image
```
Merriweather-Black.ttf
Merriweather-Lights.ttf
```

Bad character in HS-rss-feed titles
```
bda.txt
```

Twitter api tokens

```
default_vars.php - rename to vars.php with actual tokens
```

Actual code - run this in crontab
```
feed.php
```

Image creation function
```
make_image.php
```
