/**
 *@author: Salsita Software
 * August 22, 2014
 *Java Script implementation of a z-curve function
 *Source: https://github.com/salsita/geo-tree/blob/master/src/z-curve.js
**/
// z-curve implementation mapping 2D coordinates into 1D (single index) scalar
//

//module.exports = {
  // (X,Y) --> idx
  // make sure the resulting float is 53 bits max to maintain the precision
  var xy2d= function(x, y) {
    var bit = 1, max = Math.max(x,y), res = 0.0;
    while (bit <= max) { bit <<= 1; }
    bit >>= 1;

    while (bit) {
      res *= 2.0;
      if (x & bit) { res += 1.0; }
      res *= 2.0;
      if (y & bit) { res += 1.0; }
      bit >>= 1;
    }
    return res;
  }
//};
