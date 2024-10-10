# TestToolkit

![](assets/masthead.png)

> <sub><sup>Inspired by Hephaistos, the divine craftsman, TestToolkit empowers your testing with precision and versatility.</sup></sub>

## Overview

A versatile collection of tools for PHP testing, including access utilities, data providers, and more.

## Features

### 1. **AccessHelper**
Provides access to non-public properties and methods using PHP's reflection API, enabling modification and retrieval of otherwise inaccessible values.

### 2. **DataHelper**
Provides reusable data sets for PHPUnit, including common value types like non-strings, non-integers, booleans, and combinations of test data (Cartesian product).

### 3. **SingletonHelper**
Manages Singleton instances in tests, allowing you to back up, restore, and modify them as needed. Depends on the **[Harmonia](https://github.com/DaphneWebFramework/Harmonia)** library.

## Installation

### Adding

```
git submodule add https://github.com/DaphneWebFramework/TestToolkit.git source/classes/TestToolkit
```

### Updating

```
git submodule update --remote --merge
```

## License

TestToolkit is distributed under the Creative Commons Attribution 4.0 International License. For more information, visit [CC BY 4.0 License](https://creativecommons.org/licenses/by/4.0/).
