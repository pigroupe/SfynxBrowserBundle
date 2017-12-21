#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
#
# SfynxBrowserBundle configuration
#  
sfynx_browser:
    cookies:
        date_expire: true
        date_interval:  %cookie_lifetime% # 604800 PT4H  604800
    browscap:
        cache_dir:            "%sfynx_cache_dir%/browscap" # null : If null, use your application cache directory
        update_interval:      432000
        error_interval:       7200
        timeout:              10000
        silent:               false
```
