# Laravel json casting

- A easy solution to have decriptions of json objects POCO style, please check **tests** for examples
- The method **Object::collection** will convert an array of objects to an collection of PHP objects that extends from CastModels\Model
- The objects that extends from CastModels\Model has the method **toArray** so can be returned in a response directly