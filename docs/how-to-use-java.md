# How to use TurboCommons with Java

The Java package is under development. Its source code is in
[`packages/turbocommons-java`](../packages/turbocommons-java/), and the package
can be built with Gradle:

```bash
cd packages/turbocommons-java
bash gradlew build
```

The currently available utility API includes `ArrayUtils`, for example:

```java
import org.turbocommons.utils.ArrayUtils;

Boolean equal = ArrayUtils.isEqual(firstArray, secondArray);
```

See the [Java package README](../packages/turbocommons-java/README.md) for the
 Maven Central publishing guide.
