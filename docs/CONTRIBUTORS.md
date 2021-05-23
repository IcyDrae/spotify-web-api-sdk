## Contributing

When contributing to this repository, please open an issue first and explain the change- feature, bug, optimization?

### Setup

- ```git clone https://github.com/ReardGjoni/spotify-web-api-sdk```
- create an empty PHP project with the prerequisites described [here](../README.md#prerequisites),
- ```composer init``` 
- add the following to the composer.json:

```json
    "repositories": [
        {
            "type": "path",
            "url": "../spotify-web-api-sdk"
        }
    ]
```

- ```composer require rgjoni/spotify-web-api-sdk dev-main```

Based on the above added lines this will symlink your local copy into the vendor folder
and get you ready for development.

### Pull requests

1. Please follow this [commit naming convention](https://www.conventionalcommits.org/en/v1.0.0/)
and keep a level of verbosity(comments in the code etc.)
2. Update the README.md with details of changes, this includes any major changes that
result to changes in the usage of the library.




