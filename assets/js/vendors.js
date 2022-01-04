/**
* (c) Iconify
*
* For the full copyright and license information, please view the license.txt or license.gpl.txt
* files at https://github.com/iconify/iconify
*
* Licensed under Apache 2.0 or GPL 2.0 at your option.
* If derivative product is not compatible with one of licenses, you can pick one of licenses.
*
* @license Apache 2.0
* @license GPL 2.0
* @version 2.1.0
*/
var Iconify = (function (exports) {
    'use strict';
  
    // src/icon/index.ts
    var matchName = /^[a-z0-9]+(-[a-z0-9]+)*$/;
    var iconDefaults = Object.freeze({
      left: 0,
      top: 0,
      width: 16,
      height: 16,
      rotate: 0,
      vFlip: false,
      hFlip: false
    });
    function fullIcon(data) {
      return Object.assign({}, iconDefaults, data);
    }
  
    // src/icon/merge.ts
    function mergeIconData(icon, alias) {
      var result = Object.assign({}, icon);
      for (var key in iconDefaults) {
        var prop = key;
        if (alias[prop] !== void 0) {
          var value = alias[prop];
          if (result[prop] === void 0) {
            result[prop] = value;
            continue;
          }
          switch (prop) {
            case "rotate":
              result[prop] = (result[prop] + value) % 4;
              break;
            case "hFlip":
            case "vFlip":
              result[prop] = value !== result[prop];
              break;
            default:
              result[prop] = value;
          }
        }
      }
      return result;
    }
  
    // src/icon-set/get-icon.ts
    function getIconData$1(data, name, full) {
      if ( full === void 0 ) full = false;
  
      function getIcon(name2, iteration) {
        var _a, _b, _c, _d;
        if (data.icons[name2] !== void 0) {
          return Object.assign({}, data.icons[name2]);
        }
        if (iteration > 5) {
          return null;
        }
        if (((_a = data.aliases) == null ? void 0 : _a[name2]) !== void 0) {
          var item = (_b = data.aliases) == null ? void 0 : _b[name2];
          var result2 = getIcon(item.parent, iteration + 1);
          if (result2) {
            return mergeIconData(result2, item);
          }
          return result2;
        }
        if (iteration === 0 && ((_c = data.chars) == null ? void 0 : _c[name2]) !== void 0) {
          return getIcon((_d = data.chars) == null ? void 0 : _d[name2], iteration + 1);
        }
        return null;
      }
      var result = getIcon(name, 0);
      if (result) {
        for (var key in iconDefaults) {
          if (result[key] === void 0 && data[key] !== void 0) {
            result[key] = data[key];
          }
        }
      }
      return result && full ? fullIcon(result) : result;
    }
  
    // src/icon-set/validate.ts
    var matchChar = /^[a-f0-9]+(-[a-f0-9]+)*$/;
    function validateIconProps(item, fix) {
      for (var key in item) {
        var attr = key;
        var value = item[attr];
        var type = typeof value;
        if (type === "undefined") {
          delete item[attr];
          continue;
        }
        switch (key) {
          case "body":
          case "parent":
            if (type !== "string") {
              return key;
            }
            break;
          case "hFlip":
          case "vFlip":
          case "hidden":
            if (type !== "boolean") {
              if (fix) {
                delete item[attr];
              } else {
                return key;
              }
            }
            break;
          case "width":
          case "height":
          case "left":
          case "top":
          case "rotate":
          case "inlineHeight":
          case "inlineTop":
          case "verticalAlign":
            if (type !== "number") {
              if (fix) {
                delete item[attr];
              } else {
                return key;
              }
            }
            break;
          default:
            if (type === "object") {
              if (fix) {
                delete item[attr];
              } else {
                return key;
              }
            }
        }
      }
      return null;
    }
    function validateIconSet(obj, options) {
      var fix = !!(options == null ? void 0 : options.fix);
      if (typeof obj !== "object" || obj === null || typeof obj.icons !== "object" || !obj.icons) {
        throw new Error("Bad icon set");
      }
      var data = obj;
      if (typeof (options == null ? void 0 : options.prefix) === "string") {
        data.prefix = options.prefix;
      } else if (typeof data.prefix !== "string" || !data.prefix.match(matchName)) {
        throw new Error("Invalid prefix");
      }
      if (typeof (options == null ? void 0 : options.provider) === "string") {
        data.provider = options.provider;
      } else if (data.provider !== void 0) {
        var value = data.provider;
        if (typeof value !== "string" || value !== "" && !value.match(matchName)) {
          if (fix) {
            delete data.provider;
          } else {
            throw new Error("Invalid provider");
          }
        }
      }
      var icons = data.icons;
      Object.keys(icons).forEach(function (name) {
        if (!name.match(matchName)) {
          if (fix) {
            delete icons[name];
            return;
          }
          throw new Error(("Invalid icon name: \"" + name + "\""));
        }
        var item = icons[name];
        if (typeof item !== "object" || item === null || typeof item.body !== "string") {
          if (fix) {
            delete icons[name];
            return;
          }
          throw new Error(("Invalid icon: \"" + name + "\""));
        }
        var key = typeof item.parent === "string" ? "parent" : validateIconProps(item, fix);
        if (key !== null) {
          if (fix) {
            delete icons[name];
            return;
          }
          throw new Error(("Invalid property \"" + key + "\" in icon \"" + name + "\""));
        }
      });
      if (!Object.keys(data.icons).length) {
        throw new Error("Icon set is empty");
      }
      if (data.aliases !== void 0) {
        if (typeof data.aliases !== "object" || data.aliases === null) {
          if (fix) {
            delete data.aliases;
          } else {
            throw new Error("Invalid aliases list");
          }
        }
      }
      if (typeof data.aliases === "object") {
        var validateAlias = function(name, iteration) {
          if (validatedAliases.has(name)) {
            return !failedAliases.has(name);
          }
          var item = aliases[name];
          if (iteration > 5 || typeof item !== "object" || item === null || typeof item.parent !== "string" || !name.match(matchName)) {
            if (fix) {
              delete aliases[name];
              failedAliases.add(name);
              return false;
            }
            throw new Error(("Invalid icon alias: \"" + name + "\""));
          }
          var parent = item.parent;
          if (data.icons[parent] === void 0) {
            if (aliases[parent] === void 0 || !validateAlias(parent, iteration + 1)) {
              if (fix) {
                delete aliases[name];
                failedAliases.add(name);
                return false;
              }
              throw new Error(("Missing parent icon for alias \"" + name));
            }
          }
          if (fix && item.body !== void 0) {
            delete item.body;
          }
          var key = item.body !== void 0 ? "body" : validateIconProps(item, fix);
          if (key !== null) {
            if (fix) {
              delete aliases[name];
              failedAliases.add(name);
              return false;
            }
            throw new Error(("Invalid property \"" + key + "\" in alias \"" + name + "\""));
          }
          validatedAliases.add(name);
          return true;
        };
        var aliases = data.aliases;
        var validatedAliases = new Set();
        var failedAliases = new Set();
        Object.keys(aliases).forEach(function (name) {
          validateAlias(name, 0);
        });
        if (fix && !Object.keys(data.aliases).length) {
          delete data.aliases;
        }
      }
      Object.keys(iconDefaults).forEach(function (prop) {
        var expectedType = typeof iconDefaults[prop];
        var actualType = typeof data[prop];
        if (actualType !== "undefined" && actualType !== expectedType) {
          throw new Error(("Invalid value type for \"" + prop + "\""));
        }
      });
      if (data.chars !== void 0) {
        if (typeof data.chars !== "object" || data.chars === null) {
          if (fix) {
            delete data.chars;
          } else {
            throw new Error("Invalid characters map");
          }
        }
      }
      if (typeof data.chars === "object") {
        var chars = data.chars;
        Object.keys(chars).forEach(function (char) {
          var _a;
          if (!char.match(matchChar) || typeof chars[char] !== "string") {
            if (fix) {
              delete chars[char];
              return;
            }
            throw new Error(("Invalid character \"" + char + "\""));
          }
          var target = chars[char];
          if (data.icons[target] === void 0 && ((_a = data.aliases) == null ? void 0 : _a[target]) === void 0) {
            if (fix) {
              delete chars[char];
              return;
            }
            throw new Error(("Character \"" + char + "\" points to missing icon \"" + target + "\""));
          }
        });
        if (fix && !Object.keys(data.chars).length) {
          delete data.chars;
        }
      }
      return data;
    }
  
    // src/icon-set/parse.ts
    function isVariation(item) {
      for (var key in iconDefaults) {
        if (item[key] !== void 0) {
          return true;
        }
      }
      return false;
    }
    function parseIconSet(data, callback, options) {
      options = options || {};
      var names = [];
      if (typeof data !== "object" || typeof data.icons !== "object") {
        return names;
      }
      var validate = options.validate;
      if (validate !== false) {
        try {
          validateIconSet(data, typeof validate === "object" ? validate : { fix: true });
        } catch (err) {
          return names;
        }
      }
      if (data.not_found instanceof Array) {
        data.not_found.forEach(function (name) {
          callback(name, null);
          names.push(name);
        });
      }
      var icons = data.icons;
      Object.keys(icons).forEach(function (name) {
        var iconData = getIconData$1(data, name, true);
        if (iconData) {
          callback(name, iconData);
          names.push(name);
        }
      });
      var parseAliases = options.aliases || "all";
      if (parseAliases !== "none" && typeof data.aliases === "object") {
        var aliases = data.aliases;
        Object.keys(aliases).forEach(function (name) {
          if (parseAliases === "variations" && isVariation(aliases[name])) {
            return;
          }
          var iconData = getIconData$1(data, name, true);
          if (iconData) {
            callback(name, iconData);
            names.push(name);
          }
        });
      }
      return names;
    }
  
    // src/icon/name.ts
    var stringToIcon = function (value, validate, allowSimpleName, provider) {
      if ( provider === void 0 ) provider = "";
  
      var colonSeparated = value.split(":");
      if (value.slice(0, 1) === "@") {
        if (colonSeparated.length < 2 || colonSeparated.length > 3) {
          return null;
        }
        provider = colonSeparated.shift().slice(1);
      }
      if (colonSeparated.length > 3 || !colonSeparated.length) {
        return null;
      }
      if (colonSeparated.length > 1) {
        var name2 = colonSeparated.pop();
        var prefix = colonSeparated.pop();
        var result = {
          provider: colonSeparated.length > 0 ? colonSeparated[0] : provider,
          prefix: prefix,
          name: name2
        };
        return validate && !validateIcon(result) ? null : result;
      }
      var name = colonSeparated[0];
      var dashSeparated = name.split("-");
      if (dashSeparated.length > 1) {
        var result$1 = {
          provider: provider,
          prefix: dashSeparated.shift(),
          name: dashSeparated.join("-")
        };
        return validate && !validateIcon(result$1) ? null : result$1;
      }
      if (allowSimpleName && provider === "") {
        var result$2 = {
          provider: provider,
          prefix: "",
          name: name
        };
        return validate && !validateIcon(result$2, allowSimpleName) ? null : result$2;
      }
      return null;
    };
    var validateIcon = function (icon, allowSimpleName) {
      if (!icon) {
        return false;
      }
      return !!((icon.provider === "" || icon.provider.match(matchName)) && (allowSimpleName && icon.prefix === "" || icon.prefix.match(matchName)) && icon.name.match(matchName));
    };
  
    // src/storage/storage.ts
    var storage$1 = Object.create(null);
    function newStorage(provider, prefix) {
      return {
        provider: provider,
        prefix: prefix,
        icons: Object.create(null),
        missing: Object.create(null)
      };
    }
    function getStorage(provider, prefix) {
      if (storage$1[provider] === void 0) {
        storage$1[provider] = Object.create(null);
      }
      var providerStorage = storage$1[provider];
      if (providerStorage[prefix] === void 0) {
        providerStorage[prefix] = newStorage(provider, prefix);
      }
      return providerStorage[prefix];
    }
    function addIconSet(storage2, data) {
      var t = Date.now();
      return parseIconSet(data, function (name, icon) {
        if (icon) {
          storage2.icons[name] = icon;
        } else {
          storage2.missing[name] = t;
        }
      });
    }
    function addIconToStorage(storage2, name, icon) {
      try {
        if (typeof icon.body === "string") {
          storage2.icons[name] = Object.freeze(fullIcon(icon));
          return true;
        }
      } catch (err) {
      }
      return false;
    }
    function getIconFromStorage(storage2, name) {
      var value = storage2.icons[name];
      return value === void 0 ? null : value;
    }
    function listIcons(provider, prefix) {
      var allIcons = [];
      var providers;
      if (typeof provider === "string") {
        providers = [provider];
      } else {
        providers = Object.keys(storage$1);
      }
      providers.forEach(function (provider2) {
        var prefixes;
        if (typeof provider2 === "string" && typeof prefix === "string") {
          prefixes = [prefix];
        } else {
          prefixes = storage$1[provider2] === void 0 ? [] : Object.keys(storage$1[provider2]);
        }
        prefixes.forEach(function (prefix2) {
          var storage2 = getStorage(provider2, prefix2);
          var icons = Object.keys(storage2.icons).map(function (name) { return (provider2 !== "" ? "@" + provider2 + ":" : "") + prefix2 + ":" + name; });
          allIcons = allIcons.concat(icons);
        });
      });
      return allIcons;
    }
  
    // src/storage/functions.ts
    var simpleNames = false;
    function allowSimpleNames(allow) {
      if (typeof allow === "boolean") {
        simpleNames = allow;
      }
      return simpleNames;
    }
    function getIconData(name) {
      var icon = typeof name === "string" ? stringToIcon(name, true, simpleNames) : name;
      return icon ? getIconFromStorage(getStorage(icon.provider, icon.prefix), icon.name) : null;
    }
    function addIcon(name, data) {
      var icon = stringToIcon(name, true, simpleNames);
      if (!icon) {
        return false;
      }
      var storage = getStorage(icon.provider, icon.prefix);
      return addIconToStorage(storage, icon.name, data);
    }
    function addCollection(data, provider) {
      if (typeof data !== "object") {
        return false;
      }
      if (typeof provider !== "string") {
        provider = typeof data.provider === "string" ? data.provider : "";
      }
      if (simpleNames && provider === "" && (typeof data.prefix !== "string" || data.prefix === "")) {
        var added = false;
        parseIconSet(data, function (name, icon) {
          if (icon && addIcon(name, icon)) {
            added = true;
          }
        }, {
          validate: {
            fix: true,
            prefix: ""
          }
        });
        return added;
      }
      if (typeof data.prefix !== "string" || !validateIcon({
        provider: provider,
        prefix: data.prefix,
        name: "a"
      })) {
        return false;
      }
      var storage = getStorage(provider, data.prefix);
      return !!addIconSet(storage, data);
    }
    function iconExists(name) {
      return getIconData(name) !== null;
    }
    function getIcon(name) {
      var result = getIconData(name);
      return result ? Object.assign({}, result) : null;
    }
  
    // src/customisations/index.ts
    var defaults = Object.freeze({
      inline: false,
      width: null,
      height: null,
      hAlign: "center",
      vAlign: "middle",
      slice: false,
      hFlip: false,
      vFlip: false,
      rotate: 0
    });
    function mergeCustomisations(defaults2, item) {
      var result = {};
      for (var key in defaults2) {
        var attr = key;
        result[attr] = defaults2[attr];
        if (item[attr] === void 0) {
          continue;
        }
        var value = item[attr];
        switch (attr) {
          case "inline":
          case "slice":
            if (typeof value === "boolean") {
              result[attr] = value;
            }
            break;
          case "hFlip":
          case "vFlip":
            if (value === true) {
              result[attr] = !result[attr];
            }
            break;
          case "hAlign":
          case "vAlign":
            if (typeof value === "string" && value !== "") {
              result[attr] = value;
            }
            break;
          case "width":
          case "height":
            if (typeof value === "string" && value !== "" || typeof value === "number" && value || value === null) {
              result[attr] = value;
            }
            break;
          case "rotate":
            if (typeof value === "number") {
              result[attr] += value;
            }
            break;
        }
      }
      return result;
    }
  
    // src/svg/size.ts
    var unitsSplit = /(-?[0-9.]*[0-9]+[0-9.]*)/g;
    var unitsTest = /^-?[0-9.]*[0-9]+[0-9.]*$/g;
    function calculateSize(size, ratio, precision) {
      if (ratio === 1) {
        return size;
      }
      precision = precision === void 0 ? 100 : precision;
      if (typeof size === "number") {
        return Math.ceil(size * ratio * precision) / precision;
      }
      if (typeof size !== "string") {
        return size;
      }
      var oldParts = size.split(unitsSplit);
      if (oldParts === null || !oldParts.length) {
        return size;
      }
      var newParts = [];
      var code = oldParts.shift();
      var isNumber = unitsTest.test(code);
      while (true) {
        if (isNumber) {
          var num = parseFloat(code);
          if (isNaN(num)) {
            newParts.push(code);
          } else {
            newParts.push(Math.ceil(num * ratio * precision) / precision);
          }
        } else {
          newParts.push(code);
        }
        code = oldParts.shift();
        if (code === void 0) {
          return newParts.join("");
        }
        isNumber = !isNumber;
      }
    }
  
    // src/svg/build.ts
    function preserveAspectRatio(props) {
      var result = "";
      switch (props.hAlign) {
        case "left":
          result += "xMin";
          break;
        case "right":
          result += "xMax";
          break;
        default:
          result += "xMid";
      }
      switch (props.vAlign) {
        case "top":
          result += "YMin";
          break;
        case "bottom":
          result += "YMax";
          break;
        default:
          result += "YMid";
      }
      result += props.slice ? " slice" : " meet";
      return result;
    }
    function iconToSVG(icon, customisations) {
      var box = {
        left: icon.left,
        top: icon.top,
        width: icon.width,
        height: icon.height
      };
      var body = icon.body;
      [icon, customisations].forEach(function (props) {
        var transformations = [];
        var hFlip = props.hFlip;
        var vFlip = props.vFlip;
        var rotation = props.rotate;
        if (hFlip) {
          if (vFlip) {
            rotation += 2;
          } else {
            transformations.push("translate(" + (box.width + box.left) + " " + (0 - box.top) + ")");
            transformations.push("scale(-1 1)");
            box.top = box.left = 0;
          }
        } else if (vFlip) {
          transformations.push("translate(" + (0 - box.left) + " " + (box.height + box.top) + ")");
          transformations.push("scale(1 -1)");
          box.top = box.left = 0;
        }
        var tempValue;
        if (rotation < 0) {
          rotation -= Math.floor(rotation / 4) * 4;
        }
        rotation = rotation % 4;
        switch (rotation) {
          case 1:
            tempValue = box.height / 2 + box.top;
            transformations.unshift("rotate(90 " + tempValue + " " + tempValue + ")");
            break;
          case 2:
            transformations.unshift("rotate(180 " + (box.width / 2 + box.left) + " " + (box.height / 2 + box.top) + ")");
            break;
          case 3:
            tempValue = box.width / 2 + box.left;
            transformations.unshift("rotate(-90 " + tempValue + " " + tempValue + ")");
            break;
        }
        if (rotation % 2 === 1) {
          if (box.left !== 0 || box.top !== 0) {
            tempValue = box.left;
            box.left = box.top;
            box.top = tempValue;
          }
          if (box.width !== box.height) {
            tempValue = box.width;
            box.width = box.height;
            box.height = tempValue;
          }
        }
        if (transformations.length) {
          body = '<g transform="' + transformations.join(" ") + '">' + body + "</g>";
        }
      });
      var width, height;
      if (customisations.width === null && customisations.height === null) {
        height = "1em";
        width = calculateSize(height, box.width / box.height);
      } else if (customisations.width !== null && customisations.height !== null) {
        width = customisations.width;
        height = customisations.height;
      } else if (customisations.height !== null) {
        height = customisations.height;
        width = calculateSize(height, box.width / box.height);
      } else {
        width = customisations.width;
        height = calculateSize(width, box.height / box.width);
      }
      if (width === "auto") {
        width = box.width;
      }
      if (height === "auto") {
        height = box.height;
      }
      width = typeof width === "string" ? width : width + "";
      height = typeof height === "string" ? height : height + "";
      var result = {
        attributes: {
          width: width,
          height: height,
          preserveAspectRatio: preserveAspectRatio(customisations),
          viewBox: box.left + " " + box.top + " " + box.width + " " + box.height
        },
        body: body
      };
      if (customisations.inline) {
        result.inline = true;
      }
      return result;
    }
  
    // src/builder/functions.ts
    function buildIcon(icon, customisations) {
      return iconToSVG(fullIcon(icon), customisations ? mergeCustomisations(defaults, customisations) : defaults);
    }
  
    // src/svg/id.ts
    var regex = /\sid="(\S+)"/g;
    var randomPrefix = "IconifyId-" + Date.now().toString(16) + "-" + (Math.random() * 16777216 | 0).toString(16) + "-";
    var counter = 0;
    function replaceIDs(body, prefix) {
      if ( prefix === void 0 ) prefix = randomPrefix;
  
      var ids = [];
      var match;
      while (match = regex.exec(body)) {
        ids.push(match[1]);
      }
      if (!ids.length) {
        return body;
      }
      ids.forEach(function (id) {
        var newID = typeof prefix === "function" ? prefix(id) : prefix + counter++;
        var escapedID = id.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
        body = body.replace(new RegExp('([#;"])(' + escapedID + ')([")]|\\.[a-z])', "g"), "$1" + newID + "$3");
      });
      return body;
    }
  
    // src/browser-storage/index.ts
    var cacheVersion = "iconify2";
    var cachePrefix = "iconify";
    var countKey = cachePrefix + "-count";
    var versionKey = cachePrefix + "-version";
    var hour = 36e5;
    var cacheExpiration = 168;
    var config = {
      local: true,
      session: true
    };
    var loaded = false;
    var count = {
      local: 0,
      session: 0
    };
    var emptyList = {
      local: [],
      session: []
    };
    var _window$2 = typeof window === "undefined" ? {} : window;
    function getGlobal$1(key) {
      var attr = key + "Storage";
      try {
        if (_window$2 && _window$2[attr] && typeof _window$2[attr].length === "number") {
          return _window$2[attr];
        }
      } catch (err) {
      }
      config[key] = false;
      return null;
    }
    function setCount(storage, key, value) {
      try {
        storage.setItem(countKey, value + "");
        count[key] = value;
        return true;
      } catch (err) {
        return false;
      }
    }
    function getCount(storage) {
      var count2 = storage.getItem(countKey);
      if (count2) {
        var total = parseInt(count2);
        return total ? total : 0;
      }
      return 0;
    }
    function initCache(storage, key) {
      try {
        storage.setItem(versionKey, cacheVersion);
      } catch (err) {
      }
      setCount(storage, key, 0);
    }
    function destroyCache(storage) {
      try {
        var total = getCount(storage);
        for (var i = 0; i < total; i++) {
          storage.removeItem(cachePrefix + i);
        }
      } catch (err) {
      }
    }
    var loadCache = function () {
      if (loaded) {
        return;
      }
      loaded = true;
      var minTime = Math.floor(Date.now() / hour) - cacheExpiration;
      function load(key) {
        var func = getGlobal$1(key);
        if (!func) {
          return;
        }
        var getItem = function (index) {
          var name = cachePrefix + index;
          var item = func.getItem(name);
          if (typeof item !== "string") {
            return false;
          }
          var valid = true;
          try {
            var data = JSON.parse(item);
            if (typeof data !== "object" || typeof data.cached !== "number" || data.cached < minTime || typeof data.provider !== "string" || typeof data.data !== "object" || typeof data.data.prefix !== "string") {
              valid = false;
            } else {
              var provider = data.provider;
              var prefix = data.data.prefix;
              var storage = getStorage(provider, prefix);
              valid = addIconSet(storage, data.data).length > 0;
            }
          } catch (err) {
            valid = false;
          }
          if (!valid) {
            func.removeItem(name);
          }
          return valid;
        };
        try {
          var version = func.getItem(versionKey);
          if (version !== cacheVersion) {
            if (version) {
              destroyCache(func);
            }
            initCache(func, key);
            return;
          }
          var total = getCount(func);
          for (var i = total - 1; i >= 0; i--) {
            if (!getItem(i)) {
              if (i === total - 1) {
                total--;
              } else {
                emptyList[key].push(i);
              }
            }
          }
          setCount(func, key, total);
        } catch (err) {
        }
      }
      for (var key in config) {
        load(key);
      }
    };
    var storeCache = function (provider, data) {
      if (!loaded) {
        loadCache();
      }
      function store(key) {
        if (!config[key]) {
          return false;
        }
        var func = getGlobal$1(key);
        if (!func) {
          return false;
        }
        var index = emptyList[key].shift();
        if (index === void 0) {
          index = count[key];
          if (!setCount(func, key, index + 1)) {
            return false;
          }
        }
        try {
          var item = {
            cached: Math.floor(Date.now() / hour),
            provider: provider,
            data: data
          };
          func.setItem(cachePrefix + index, JSON.stringify(item));
        } catch (err) {
          return false;
        }
        return true;
      }
      if (!store("local")) {
        store("session");
      }
    };
  
    // src/cache.ts
    var cache = {};
  
    // src/browser-storage/functions.ts
    function toggleBrowserCache(storage, value) {
      switch (storage) {
        case "local":
        case "session":
          config[storage] = value;
          break;
        case "all":
          for (var key in config) {
            config[key] = value;
          }
          break;
      }
    }
  
    // src/api/modules.ts
    var storage = Object.create(null);
    function setAPIModule(provider, item) {
      storage[provider] = item;
    }
    function getAPIModule(provider) {
      return storage[provider] || storage[""];
    }
  
    // src/api/config.ts
    function createAPIConfig(source) {
      var resources;
      if (typeof source.resources === "string") {
        resources = [source.resources];
      } else {
        resources = source.resources;
        if (!(resources instanceof Array) || !resources.length) {
          return null;
        }
      }
      var result = {
        resources: resources,
        path: source.path === void 0 ? "/" : source.path,
        maxURL: source.maxURL ? source.maxURL : 500,
        rotate: source.rotate ? source.rotate : 750,
        timeout: source.timeout ? source.timeout : 5e3,
        random: source.random === true,
        index: source.index ? source.index : 0,
        dataAfterTimeout: source.dataAfterTimeout !== false
      };
      return result;
    }
    var configStorage = Object.create(null);
    var fallBackAPISources = [
      "https://api.simplesvg.com",
      "https://api.unisvg.com"
    ];
    var fallBackAPI = [];
    while (fallBackAPISources.length > 0) {
      if (fallBackAPISources.length === 1) {
        fallBackAPI.push(fallBackAPISources.shift());
      } else {
        if (Math.random() > 0.5) {
          fallBackAPI.push(fallBackAPISources.shift());
        } else {
          fallBackAPI.push(fallBackAPISources.pop());
        }
      }
    }
    configStorage[""] = createAPIConfig({
      resources: ["https://api.iconify.design"].concat(fallBackAPI)
    });
    function addAPIProvider(provider, customConfig) {
      var config = createAPIConfig(customConfig);
      if (config === null) {
        return false;
      }
      configStorage[provider] = config;
      return true;
    }
    function getAPIConfig(provider) {
      return configStorage[provider];
    }
    function listAPIProviders() {
      return Object.keys(configStorage);
    }
  
    // src/api/params.ts
    var mergeParams = function (base, params) {
      var result = base, hasParams = result.indexOf("?") !== -1;
      function paramToString(value) {
        switch (typeof value) {
          case "boolean":
            return value ? "true" : "false";
          case "number":
            return encodeURIComponent(value);
          case "string":
            return encodeURIComponent(value);
          default:
            throw new Error("Invalid parameter");
        }
      }
      Object.keys(params).forEach(function (key) {
        var value;
        try {
          value = paramToString(params[key]);
        } catch (err) {
          return;
        }
        result += (hasParams ? "&" : "?") + encodeURIComponent(key) + "=" + value;
        hasParams = true;
      });
      return result;
    };
  
    // src/api/modules/jsonp.ts
    var rootVar = null;
    var rootVarName = null;
    var maxLengthCache$1 = Object.create(null);
    var pathCache$1 = Object.create(null);
    function hash(str) {
      var total = 0, i;
      for (i = str.length - 1; i >= 0; i--) {
        total += str.charCodeAt(i);
      }
      return total % 999;
    }
    function getGlobal() {
      if (rootVar === null) {
        var globalRoot = self;
        var prefix = "Iconify";
        var extraPrefix = ".cb";
        if (globalRoot[prefix] === void 0) {
          prefix = "IconifyJSONP";
          extraPrefix = "";
          if (globalRoot[prefix] === void 0) {
            globalRoot[prefix] = Object.create(null);
          }
          rootVar = globalRoot[prefix];
        } else {
          var iconifyRoot = globalRoot[prefix];
          if (iconifyRoot.cb === void 0) {
            iconifyRoot.cb = Object.create(null);
          }
          rootVar = iconifyRoot.cb;
        }
        rootVarName = prefix + extraPrefix + ".{cb}";
      }
      return rootVar;
    }
    function calculateMaxLength$1(provider, prefix) {
      var config = getAPIConfig(provider);
      if (!config) {
        return 0;
      }
      var result;
      if (!config.maxURL) {
        result = 0;
      } else {
        var maxHostLength = 0;
        config.resources.forEach(function (item) {
          var host = item;
          maxHostLength = Math.max(maxHostLength, host.length);
        });
        getGlobal();
        var url = mergeParams(prefix + ".js", {
          icons: "",
          callback: rootVarName
        });
        result = config.maxURL - maxHostLength - config.path.length - url.length;
      }
      var cacheKey = provider + ":" + prefix;
      pathCache$1[cacheKey] = config.path;
      maxLengthCache$1[cacheKey] = result;
      return result;
    }
    var prepare$1 = function (provider, prefix, icons) {
      var results = [];
      var cacheKey = provider + ":" + prefix;
      var maxLength = maxLengthCache$1[cacheKey];
      if (maxLength === void 0) {
        maxLength = calculateMaxLength$1(provider, prefix);
      }
      var type = "icons";
      var item = {
        type: type,
        provider: provider,
        prefix: prefix,
        icons: []
      };
      var length = 0;
      icons.forEach(function (name, index) {
        length += name.length + 1;
        if (length >= maxLength && index > 0) {
          results.push(item);
          item = {
            type: type,
            provider: provider,
            prefix: prefix,
            icons: []
          };
          length = name.length;
        }
        item.icons.push(name);
      });
      results.push(item);
      return results;
    };
    var send$1 = function (host, params, status) {
      if (params.type !== "icons") {
        status.done(void 0, 400);
        return;
      }
      var provider = params.provider;
      var prefix = params.prefix;
      var icons = params.icons;
      var iconsList = icons.join(",");
      var cacheKey = provider + ":" + prefix;
      var cbPrefix = prefix.split("-").shift().slice(0, 3);
      var global = getGlobal();
      var cbCounter = hash(provider + ":" + host + ":" + prefix + ":" + iconsList);
      while (global[cbPrefix + cbCounter] !== void 0) {
        cbCounter++;
      }
      var callbackName = cbPrefix + cbCounter;
      var url = mergeParams(prefix + ".js", {
        icons: iconsList,
        callback: rootVarName.replace("{cb}", callbackName)
      });
      var path = pathCache$1[cacheKey] + url;
      global[callbackName] = function (data) {
        delete global[callbackName];
        status.done(data);
      };
      var uri = host + path;
      var script = document.createElement("script");
      script.type = "text/javascript";
      script.async = true;
      script.src = uri;
      document.head.appendChild(script);
    };
    var jsonpAPIModule = { prepare: prepare$1, send: send$1 };
  
    // src/api/modules/fetch.ts
    var maxLengthCache = Object.create(null);
    var pathCache = Object.create(null);
    var detectFetch = function () {
      var callback;
      try {
        callback = fetch;
        if (typeof callback === "function") {
          return callback;
        }
      } catch (err) {
      }
      try {
        var chunk = String.fromCharCode(114) + String.fromCharCode(101);
        var req = global[chunk + "qui" + chunk];
        callback = req("cross-fetch");
        if (typeof callback === "function") {
          return callback;
        }
      } catch (err$1) {
      }
      return null;
    };
    var fetchModule = detectFetch();
    function setFetch$1(fetch2) {
      fetchModule = fetch2;
    }
    function getFetch() {
      return fetchModule;
    }
    function calculateMaxLength(provider, prefix) {
      var config = getAPIConfig(provider);
      if (!config) {
        return 0;
      }
      var result;
      if (!config.maxURL) {
        result = 0;
      } else {
        var maxHostLength = 0;
        config.resources.forEach(function (item) {
          var host = item;
          maxHostLength = Math.max(maxHostLength, host.length);
        });
        var url = mergeParams(prefix + ".json", {
          icons: ""
        });
        result = config.maxURL - maxHostLength - config.path.length - url.length;
      }
      var cacheKey = provider + ":" + prefix;
      pathCache[provider] = config.path;
      maxLengthCache[cacheKey] = result;
      return result;
    }
    var prepare = function (provider, prefix, icons) {
      var results = [];
      var maxLength = maxLengthCache[prefix];
      if (maxLength === void 0) {
        maxLength = calculateMaxLength(provider, prefix);
      }
      var type = "icons";
      var item = {
        type: type,
        provider: provider,
        prefix: prefix,
        icons: []
      };
      var length = 0;
      icons.forEach(function (name, index) {
        length += name.length + 1;
        if (length >= maxLength && index > 0) {
          results.push(item);
          item = {
            type: type,
            provider: provider,
            prefix: prefix,
            icons: []
          };
          length = name.length;
        }
        item.icons.push(name);
      });
      results.push(item);
      return results;
    };
    function getPath(provider) {
      if (typeof provider === "string") {
        if (pathCache[provider] === void 0) {
          var config = getAPIConfig(provider);
          if (!config) {
            return "/";
          }
          pathCache[provider] = config.path;
        }
        return pathCache[provider];
      }
      return "/";
    }
    var send = function (host, params, status) {
      if (!fetchModule) {
        status.done(void 0, 424);
        return;
      }
      var path = getPath(params.provider);
      switch (params.type) {
        case "icons": {
          var prefix = params.prefix;
          var icons = params.icons;
          var iconsList = icons.join(",");
          path += mergeParams(prefix + ".json", {
            icons: iconsList
          });
          break;
        }
        case "custom": {
          var uri = params.uri;
          path += uri.slice(0, 1) === "/" ? uri.slice(1) : uri;
          break;
        }
        default:
          status.done(void 0, 400);
          return;
      }
      var defaultError = 503;
      fetchModule(host + path).then(function (response) {
        if (response.status !== 200) {
          setTimeout(function () {
            status.done(void 0, response.status);
          });
          return;
        }
        defaultError = 501;
        return response.json();
      }).then(function (data) {
        if (typeof data !== "object" || data === null) {
          setTimeout(function () {
            status.done(void 0, defaultError);
          });
          return;
        }
        setTimeout(function () {
          status.done(data);
        });
      }).catch(function () {
        status.done(void 0, defaultError);
      });
    };
    var fetchAPIModule = {
      prepare: prepare,
      send: send
    };
  
    // src/icon/sort.ts
    function sortIcons(icons) {
      var result = {
        loaded: [],
        missing: [],
        pending: []
      };
      var storage = Object.create(null);
      icons.sort(function (a, b) {
        if (a.provider !== b.provider) {
          return a.provider.localeCompare(b.provider);
        }
        if (a.prefix !== b.prefix) {
          return a.prefix.localeCompare(b.prefix);
        }
        return a.name.localeCompare(b.name);
      });
      var lastIcon = {
        provider: "",
        prefix: "",
        name: ""
      };
      icons.forEach(function (icon) {
        if (lastIcon.name === icon.name && lastIcon.prefix === icon.prefix && lastIcon.provider === icon.provider) {
          return;
        }
        lastIcon = icon;
        var provider = icon.provider;
        var prefix = icon.prefix;
        var name = icon.name;
        if (storage[provider] === void 0) {
          storage[provider] = Object.create(null);
        }
        var providerStorage = storage[provider];
        if (providerStorage[prefix] === void 0) {
          providerStorage[prefix] = getStorage(provider, prefix);
        }
        var localStorage = providerStorage[prefix];
        var list;
        if (localStorage.icons[name] !== void 0) {
          list = result.loaded;
        } else if (prefix === "" || localStorage.missing[name] !== void 0) {
          list = result.missing;
        } else {
          list = result.pending;
        }
        var item = {
          provider: provider,
          prefix: prefix,
          name: name
        };
        list.push(item);
      });
      return result;
    }
  
    // src/api/callbacks.ts
    var callbacks = Object.create(null);
    var pendingUpdates = Object.create(null);
    function removeCallback(sources, id) {
      sources.forEach(function (source) {
        var provider = source.provider;
        if (callbacks[provider] === void 0) {
          return;
        }
        var providerCallbacks = callbacks[provider];
        var prefix = source.prefix;
        var items = providerCallbacks[prefix];
        if (items) {
          providerCallbacks[prefix] = items.filter(function (row) { return row.id !== id; });
        }
      });
    }
    function updateCallbacks(provider, prefix) {
      if (pendingUpdates[provider] === void 0) {
        pendingUpdates[provider] = Object.create(null);
      }
      var providerPendingUpdates = pendingUpdates[provider];
      if (!providerPendingUpdates[prefix]) {
        providerPendingUpdates[prefix] = true;
        setTimeout(function () {
          providerPendingUpdates[prefix] = false;
          if (callbacks[provider] === void 0 || callbacks[provider][prefix] === void 0) {
            return;
          }
          var items = callbacks[provider][prefix].slice(0);
          if (!items.length) {
            return;
          }
          var storage = getStorage(provider, prefix);
          var hasPending = false;
          items.forEach(function (item) {
            var icons = item.icons;
            var oldLength = icons.pending.length;
            icons.pending = icons.pending.filter(function (icon) {
              if (icon.prefix !== prefix) {
                return true;
              }
              var name = icon.name;
              if (storage.icons[name] !== void 0) {
                icons.loaded.push({
                  provider: provider,
                  prefix: prefix,
                  name: name
                });
              } else if (storage.missing[name] !== void 0) {
                icons.missing.push({
                  provider: provider,
                  prefix: prefix,
                  name: name
                });
              } else {
                hasPending = true;
                return true;
              }
              return false;
            });
            if (icons.pending.length !== oldLength) {
              if (!hasPending) {
                removeCallback([
                  {
                    provider: provider,
                    prefix: prefix
                  }
                ], item.id);
              }
              item.callback(icons.loaded.slice(0), icons.missing.slice(0), icons.pending.slice(0), item.abort);
            }
          });
        });
      }
    }
    var idCounter = 0;
    function storeCallback(callback, icons, pendingSources) {
      var id = idCounter++;
      var abort = removeCallback.bind(null, pendingSources, id);
      if (!icons.pending.length) {
        return abort;
      }
      var item = {
        id: id,
        icons: icons,
        callback: callback,
        abort: abort
      };
      pendingSources.forEach(function (source) {
        var provider = source.provider;
        var prefix = source.prefix;
        if (callbacks[provider] === void 0) {
          callbacks[provider] = Object.create(null);
        }
        var providerCallbacks = callbacks[provider];
        if (providerCallbacks[prefix] === void 0) {
          providerCallbacks[prefix] = [];
        }
        providerCallbacks[prefix].push(item);
      });
      return abort;
    }
  
    // src/icon/list.ts
    function listToIcons(list, validate, simpleNames) {
      if ( validate === void 0 ) validate = true;
      if ( simpleNames === void 0 ) simpleNames = false;
  
      var result = [];
      list.forEach(function (item) {
        var icon = typeof item === "string" ? stringToIcon(item, false, simpleNames) : item;
        if (!validate || validateIcon(icon, simpleNames)) {
          result.push({
            provider: icon.provider,
            prefix: icon.prefix,
            name: icon.name
          });
        }
      });
      return result;
    }
  
    // src/config.ts
    var defaultConfig = {
      resources: [],
      index: 0,
      timeout: 2e3,
      rotate: 750,
      random: false,
      dataAfterTimeout: false
    };
  
    // src/query.ts
    function sendQuery(config, payload, query, done, success) {
      var resourcesCount = config.resources.length;
      var startIndex = config.random ? Math.floor(Math.random() * resourcesCount) : config.index;
      var resources;
      if (config.random) {
        var list = config.resources.slice(0);
        resources = [];
        while (list.length > 1) {
          var nextIndex = Math.floor(Math.random() * list.length);
          resources.push(list[nextIndex]);
          list = list.slice(0, nextIndex).concat(list.slice(nextIndex + 1));
        }
        resources = resources.concat(list);
      } else {
        resources = config.resources.slice(startIndex).concat(config.resources.slice(0, startIndex));
      }
      var startTime = Date.now();
      var status = "pending";
      var queriesSent = 0;
      var lastError = void 0;
      var timer = null;
      var queue = [];
      var doneCallbacks = [];
      if (typeof done === "function") {
        doneCallbacks.push(done);
      }
      function resetTimer() {
        if (timer) {
          clearTimeout(timer);
          timer = null;
        }
      }
      function abort() {
        if (status === "pending") {
          status = "aborted";
        }
        resetTimer();
        queue.forEach(function (item) {
          if (item.abort) {
            item.abort();
          }
          if (item.status === "pending") {
            item.status = "aborted";
          }
        });
        queue = [];
      }
      function subscribe(callback, overwrite) {
        if (overwrite) {
          doneCallbacks = [];
        }
        if (typeof callback === "function") {
          doneCallbacks.push(callback);
        }
      }
      function getQueryStatus() {
        return {
          startTime: startTime,
          payload: payload,
          status: status,
          queriesSent: queriesSent,
          queriesPending: queue.length,
          subscribe: subscribe,
          abort: abort
        };
      }
      function failQuery() {
        status = "failed";
        doneCallbacks.forEach(function (callback) {
          callback(void 0, lastError);
        });
      }
      function clearQueue() {
        queue = queue.filter(function (item) {
          if (item.status === "pending") {
            item.status = "aborted";
          }
          if (item.abort) {
            item.abort();
          }
          return false;
        });
      }
      function moduleResponse(item, data, error) {
        var isError = data === void 0;
        queue = queue.filter(function (queued) { return queued !== item; });
        switch (status) {
          case "pending":
            break;
          case "failed":
            if (isError || !config.dataAfterTimeout) {
              return;
            }
            break;
          default:
            return;
        }
        if (isError) {
          if (error !== void 0) {
            lastError = error;
          }
          if (!queue.length) {
            if (!resources.length) {
              failQuery();
            } else {
              execNext();
            }
          }
          return;
        }
        resetTimer();
        clearQueue();
        if (success && !config.random) {
          var index = config.resources.indexOf(item.resource);
          if (index !== -1 && index !== config.index) {
            success(index);
          }
        }
        status = "completed";
        doneCallbacks.forEach(function (callback) {
          callback(data);
        });
      }
      function execNext() {
        if (status !== "pending") {
          return;
        }
        resetTimer();
        var resource = resources.shift();
        if (resource === void 0) {
          if (queue.length) {
            var timeout2 = typeof config.timeout === "function" ? config.timeout(startTime) : config.timeout;
            if (timeout2) {
              timer = setTimeout(function () {
                resetTimer();
                if (status === "pending") {
                  clearQueue();
                  failQuery();
                }
              }, timeout2);
              return;
            }
          }
          failQuery();
          return;
        }
        var item = {
          getQueryStatus: getQueryStatus,
          status: "pending",
          resource: resource,
          done: function (data, error) {
            moduleResponse(item, data, error);
          }
        };
        queue.push(item);
        queriesSent++;
        var timeout = typeof config.rotate === "function" ? config.rotate(queriesSent, startTime) : config.rotate;
        timer = setTimeout(execNext, timeout);
        query(resource, payload, item);
      }
      setTimeout(execNext);
      return getQueryStatus;
    }
  
    // src/index.ts
    function setConfig(config) {
      if (typeof config !== "object" || typeof config.resources !== "object" || !(config.resources instanceof Array) || !config.resources.length) {
        throw new Error("Invalid Reduncancy configuration");
      }
      var newConfig = Object.create(null);
      var key;
      for (key in defaultConfig) {
        if (config[key] !== void 0) {
          newConfig[key] = config[key];
        } else {
          newConfig[key] = defaultConfig[key];
        }
      }
      return newConfig;
    }
    function initRedundancy(cfg) {
      var config = setConfig(cfg);
      var queries = [];
      function cleanup() {
        queries = queries.filter(function (item) { return item().status === "pending"; });
      }
      function query(payload, queryCallback, doneCallback) {
        var query2 = sendQuery(config, payload, queryCallback, function (data, error) {
          cleanup();
          if (doneCallback) {
            doneCallback(data, error);
          }
        }, function (newIndex) {
          config.index = newIndex;
        });
        queries.push(query2);
        return query2;
      }
      function find(callback) {
        var result = queries.find(function (value) {
          return callback(value);
        });
        return result !== void 0 ? result : null;
      }
      var instance = {
        query: query,
        find: find,
        setIndex: function (index) {
          config.index = index;
        },
        getIndex: function () { return config.index; },
        cleanup: cleanup
      };
      return instance;
    }
  
    // src/api/query.ts
    function emptyCallback$1() {
    }
    var redundancyCache = Object.create(null);
    function getRedundancyCache(provider) {
      if (redundancyCache[provider] === void 0) {
        var config = getAPIConfig(provider);
        if (!config) {
          return;
        }
        var redundancy = initRedundancy(config);
        var cachedReundancy = {
          config: config,
          redundancy: redundancy
        };
        redundancyCache[provider] = cachedReundancy;
      }
      return redundancyCache[provider];
    }
    function sendAPIQuery(target, query, callback) {
      var redundancy;
      var send;
      if (typeof target === "string") {
        var api = getAPIModule(target);
        if (!api) {
          callback(void 0, 424);
          return emptyCallback$1;
        }
        send = api.send;
        var cached = getRedundancyCache(target);
        if (cached) {
          redundancy = cached.redundancy;
        }
      } else {
        var config = createAPIConfig(target);
        if (config) {
          redundancy = initRedundancy(config);
          var moduleKey = target.resources ? target.resources[0] : "";
          var api$1 = getAPIModule(moduleKey);
          if (api$1) {
            send = api$1.send;
          }
        }
      }
      if (!redundancy || !send) {
        callback(void 0, 424);
        return emptyCallback$1;
      }
      return redundancy.query(query, send, callback)().abort;
    }
  
    // src/api/icons.ts
    function emptyCallback() {
    }
    var pendingIcons = Object.create(null);
    var iconsToLoad = Object.create(null);
    var loaderFlags = Object.create(null);
    var queueFlags = Object.create(null);
    function loadedNewIcons(provider, prefix) {
      if (loaderFlags[provider] === void 0) {
        loaderFlags[provider] = Object.create(null);
      }
      var providerLoaderFlags = loaderFlags[provider];
      if (!providerLoaderFlags[prefix]) {
        providerLoaderFlags[prefix] = true;
        setTimeout(function () {
          providerLoaderFlags[prefix] = false;
          updateCallbacks(provider, prefix);
        });
      }
    }
    var errorsCache = Object.create(null);
    function loadNewIcons(provider, prefix, icons) {
      function err() {
        var key = (provider === "" ? "" : "@" + provider + ":") + prefix;
        var time = Math.floor(Date.now() / 6e4);
        if (errorsCache[key] < time) {
          errorsCache[key] = time;
          console.error('Unable to retrieve icons for "' + key + '" because API is not configured properly.');
        }
      }
      if (iconsToLoad[provider] === void 0) {
        iconsToLoad[provider] = Object.create(null);
      }
      var providerIconsToLoad = iconsToLoad[provider];
      if (queueFlags[provider] === void 0) {
        queueFlags[provider] = Object.create(null);
      }
      var providerQueueFlags = queueFlags[provider];
      if (pendingIcons[provider] === void 0) {
        pendingIcons[provider] = Object.create(null);
      }
      var providerPendingIcons = pendingIcons[provider];
      if (providerIconsToLoad[prefix] === void 0) {
        providerIconsToLoad[prefix] = icons;
      } else {
        providerIconsToLoad[prefix] = providerIconsToLoad[prefix].concat(icons).sort();
      }
      if (!providerQueueFlags[prefix]) {
        providerQueueFlags[prefix] = true;
        setTimeout(function () {
          providerQueueFlags[prefix] = false;
          var icons2 = providerIconsToLoad[prefix];
          delete providerIconsToLoad[prefix];
          var api = getAPIModule(provider);
          if (!api) {
            err();
            return;
          }
          var params = api.prepare(provider, prefix, icons2);
          params.forEach(function (item) {
            sendAPIQuery(provider, item, function (data, error) {
              var storage = getStorage(provider, prefix);
              if (typeof data !== "object") {
                if (error !== 404) {
                  return;
                }
                var t = Date.now();
                item.icons.forEach(function (name) {
                  storage.missing[name] = t;
                });
              } else {
                try {
                  var parsed = addIconSet(storage, data);
                  if (!parsed.length) {
                    return;
                  }
                  var pending = providerPendingIcons[prefix];
                  parsed.forEach(function (name) {
                    delete pending[name];
                  });
                  if (cache.store) {
                    cache.store(provider, data);
                  }
                } catch (err2) {
                  console.error(err2);
                }
              }
              loadedNewIcons(provider, prefix);
            });
          });
        });
      }
    }
    var isPending = function (icon) {
      var provider = icon.provider;
      var prefix = icon.prefix;
      return pendingIcons[provider] && pendingIcons[provider][prefix] && pendingIcons[provider][prefix][icon.name] !== void 0;
    };
    var loadIcons = function (icons, callback) {
      var cleanedIcons = listToIcons(icons, true, allowSimpleNames());
      var sortedIcons = sortIcons(cleanedIcons);
      if (!sortedIcons.pending.length) {
        var callCallback = true;
        if (callback) {
          setTimeout(function () {
            if (callCallback) {
              callback(sortedIcons.loaded, sortedIcons.missing, sortedIcons.pending, emptyCallback);
            }
          });
        }
        return function () {
          callCallback = false;
        };
      }
      var newIcons = Object.create(null);
      var sources = [];
      var lastProvider, lastPrefix;
      sortedIcons.pending.forEach(function (icon) {
        var provider = icon.provider;
        var prefix = icon.prefix;
        if (prefix === lastPrefix && provider === lastProvider) {
          return;
        }
        lastProvider = provider;
        lastPrefix = prefix;
        sources.push({
          provider: provider,
          prefix: prefix
        });
        if (pendingIcons[provider] === void 0) {
          pendingIcons[provider] = Object.create(null);
        }
        var providerPendingIcons = pendingIcons[provider];
        if (providerPendingIcons[prefix] === void 0) {
          providerPendingIcons[prefix] = Object.create(null);
        }
        if (newIcons[provider] === void 0) {
          newIcons[provider] = Object.create(null);
        }
        var providerNewIcons = newIcons[provider];
        if (providerNewIcons[prefix] === void 0) {
          providerNewIcons[prefix] = [];
        }
      });
      var time = Date.now();
      sortedIcons.pending.forEach(function (icon) {
        var provider = icon.provider;
        var prefix = icon.prefix;
        var name = icon.name;
        var pendingQueue = pendingIcons[provider][prefix];
        if (pendingQueue[name] === void 0) {
          pendingQueue[name] = time;
          newIcons[provider][prefix].push(name);
        }
      });
      sources.forEach(function (source) {
        var provider = source.provider;
        var prefix = source.prefix;
        if (newIcons[provider][prefix].length) {
          loadNewIcons(provider, prefix, newIcons[provider][prefix]);
        }
      });
      return callback ? storeCallback(callback, sortedIcons, sources) : emptyCallback;
    };
  
    /**
     * Names of properties to add to nodes
     */
    var elementFinderProperty = ('iconifyFinder' + Date.now());
    var elementDataProperty = ('iconifyData' + Date.now());
  
    /**
     * Replace element with SVG
     */
    function renderIconInPlaceholder(placeholder, customisations, iconData, returnString) {
        // Create placeholder. Why placeholder? IE11 doesn't support innerHTML method on SVG.
        var span;
        try {
            span = document.createElement('span');
        }
        catch (err) {
            return returnString ? '' : null;
        }
        var data = iconToSVG(iconData, mergeCustomisations(defaults, customisations));
        // Placeholder properties
        var placeholderElement = placeholder.element;
        var finder = placeholder.finder;
        var name = placeholder.name;
        // Get class name
        var placeholderClassName = placeholderElement
            ? placeholderElement.getAttribute('class')
            : '';
        var filteredClassList = finder
            ? finder.classFilter(placeholderClassName ? placeholderClassName.split(/\s+/) : [])
            : [];
        var className = 'iconify iconify--' +
            name.prefix +
            (name.provider === '' ? '' : ' iconify--' + name.provider) +
            (filteredClassList.length ? ' ' + filteredClassList.join(' ') : '');
        // Generate SVG as string
        var html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="' +
            className +
            '">' +
            replaceIDs(data.body) +
            '</svg>';
        // Set HTML for placeholder
        span.innerHTML = html;
        // Get SVG element
        var svg = span.childNodes[0];
        var svgStyle = svg.style;
        // Add attributes
        var svgAttributes = data.attributes;
        Object.keys(svgAttributes).forEach(function (attr) {
            svg.setAttribute(attr, svgAttributes[attr]);
        });
        // Add custom styles
        if (data.inline) {
            svgStyle.verticalAlign = '-0.125em';
        }
        // Copy stuff from placeholder
        if (placeholderElement) {
            // Copy attributes
            var placeholderAttributes = placeholderElement.attributes;
            for (var i = 0; i < placeholderAttributes.length; i++) {
                var item = placeholderAttributes.item(i);
                if (item) {
                    var name$1 = item.name;
                    if (name$1 !== 'class' &&
                        name$1 !== 'style' &&
                        svgAttributes[name$1] === void 0) {
                        try {
                            svg.setAttribute(name$1, item.value);
                        }
                        catch (err$1) {
                            //
                        }
                    }
                }
            }
            // Copy styles
            var placeholderStyle = placeholderElement.style;
            for (var i$1 = 0; i$1 < placeholderStyle.length; i$1++) {
                var attr = placeholderStyle[i$1];
                svgStyle[attr] = placeholderStyle[attr];
            }
        }
        // Store finder specific data
        if (finder) {
            var elementData = {
                name: name,
                status: 'loaded',
                customisations: customisations,
            };
            svg[elementDataProperty] = elementData;
            svg[elementFinderProperty] = finder;
        }
        // Get result
        var result = returnString ? span.innerHTML : svg;
        // Replace placeholder
        if (placeholderElement && placeholderElement.parentNode) {
            placeholderElement.parentNode.replaceChild(svg, placeholderElement);
        }
        else {
            // Placeholder has no parent? Remove SVG parent as well
            span.removeChild(svg);
        }
        // Return new node
        return result;
    }
  
    /**
     * List of root nodes
     */
    var nodes = [];
    /**
     * Find node
     */
    function findRootNode(node) {
        for (var i = 0; i < nodes.length; i++) {
            var item = nodes[i];
            var root = typeof item.node === 'function' ? item.node() : item.node;
            if (root === node) {
                return item;
            }
        }
    }
    /**
     * Add extra root node
     */
    function addRootNode(root, autoRemove) {
        if ( autoRemove === void 0 ) autoRemove = false;
  
        var node = findRootNode(root);
        if (node) {
            // Node already exist: switch type if needed
            if (node.temporary) {
                node.temporary = autoRemove;
            }
            return node;
        }
        // Create item, add it to list, start observer
        node = {
            node: root,
            temporary: autoRemove,
        };
        nodes.push(node);
        return node;
    }
    /**
     * Add document.body node
     */
    function addBodyNode() {
        if (document.documentElement) {
            return addRootNode(document.documentElement);
        }
        nodes.push({
            node: function () {
                return document.documentElement;
            },
        });
    }
    /**
     * Remove root node
     */
    function removeRootNode(root) {
        nodes = nodes.filter(function (node) {
            var element = typeof node.node === 'function' ? node.node() : node.node;
            return root !== element;
        });
    }
    /**
     * Get list of root nodes
     */
    function listRootNodes() {
        return nodes;
    }
  
    /**
     * Execute function when DOM is ready
     */
    function onReady(callback) {
        var doc = document;
        if (doc.readyState === 'complete' ||
            (doc.readyState !== 'loading' &&
                !doc.documentElement.doScroll)) {
            callback();
        }
        else {
            doc.addEventListener('DOMContentLoaded', callback);
            window.addEventListener('load', callback);
        }
    }
  
    /**
     * Callback
     */
    var callback = null;
    /**
     * Parameters for mutation observer
     */
    var observerParams = {
        childList: true,
        subtree: true,
        attributes: true,
    };
    /**
     * Queue DOM scan
     */
    function queueScan(node) {
        if (!node.observer) {
            return;
        }
        var observer = node.observer;
        if (!observer.pendingScan) {
            observer.pendingScan = setTimeout(function () {
                delete observer.pendingScan;
                if (callback) {
                    callback(node);
                }
            });
        }
    }
    /**
     * Check mutations for added nodes
     */
    function checkMutations(node, mutations) {
        if (!node.observer) {
            return;
        }
        var observer = node.observer;
        if (!observer.pendingScan) {
            for (var i = 0; i < mutations.length; i++) {
                var item = mutations[i];
                if (
                // Check for added nodes
                (item.addedNodes && item.addedNodes.length > 0) ||
                    // Check for icon or placeholder with modified attributes
                    (item.type === 'attributes' &&
                        item.target[elementFinderProperty] !==
                            void 0)) {
                    if (!observer.paused) {
                        queueScan(node);
                    }
                    return;
                }
            }
        }
    }
    /**
     * Start/resume observer
     */
    function continueObserving(node, root) {
        node.observer.instance.observe(root, observerParams);
    }
    /**
     * Start mutation observer
     */
    function startObserver(node) {
        var observer = node.observer;
        if (observer && observer.instance) {
            // Already started
            return;
        }
        var root = typeof node.node === 'function' ? node.node() : node.node;
        if (!root) {
            // document.body is not available yet
            return;
        }
        if (!observer) {
            observer = {
                paused: 0,
            };
            node.observer = observer;
        }
        // Create new instance, observe
        observer.instance = new MutationObserver(checkMutations.bind(null, node));
        continueObserving(node, root);
        // Scan immediately
        if (!observer.paused) {
            queueScan(node);
        }
    }
    /**
     * Start all observers
     */
    function startObservers() {
        listRootNodes().forEach(startObserver);
    }
    /**
     * Stop observer
     */
    function stopObserver(node) {
        if (!node.observer) {
            return;
        }
        var observer = node.observer;
        // Stop scan
        if (observer.pendingScan) {
            clearTimeout(observer.pendingScan);
            delete observer.pendingScan;
        }
        // Disconnect observer
        if (observer.instance) {
            observer.instance.disconnect();
            delete observer.instance;
        }
    }
    /**
     * Start observer when DOM is ready
     */
    function initObserver(cb) {
        var isRestart = callback !== null;
        if (callback !== cb) {
            // Change callback and stop all pending observers
            callback = cb;
            if (isRestart) {
                listRootNodes().forEach(stopObserver);
            }
        }
        if (isRestart) {
            // Restart instances
            startObservers();
            return;
        }
        // Start observers when document is ready
        onReady(startObservers);
    }
    /**
     * Pause observing node
     */
    function pauseObservingNode(node) {
        (node ? [node] : listRootNodes()).forEach(function (node) {
            if (!node.observer) {
                node.observer = {
                    paused: 1,
                };
                return;
            }
            var observer = node.observer;
            observer.paused++;
            if (observer.paused > 1 || !observer.instance) {
                return;
            }
            // Disconnect observer
            var instance = observer.instance;
            // checkMutations(node, instance.takeRecords());
            instance.disconnect();
        });
    }
    /**
     * Pause observer
     */
    function pauseObserver(root) {
        if (root) {
            var node = findRootNode(root);
            if (node) {
                pauseObservingNode(node);
            }
        }
        else {
            pauseObservingNode();
        }
    }
    /**
     * Resume observer
     */
    function resumeObservingNode(observer) {
        (observer ? [observer] : listRootNodes()).forEach(function (node) {
            if (!node.observer) {
                // Start observer
                startObserver(node);
                return;
            }
            var observer = node.observer;
            if (observer.paused) {
                observer.paused--;
                if (!observer.paused) {
                    // Start / resume
                    var root = typeof node.node === 'function' ? node.node() : node.node;
                    if (!root) {
                        return;
                    }
                    else if (observer.instance) {
                        continueObserving(node, root);
                    }
                    else {
                        startObserver(node);
                    }
                }
            }
        });
    }
    /**
     * Resume observer
     */
    function resumeObserver(root) {
        if (root) {
            var node = findRootNode(root);
            if (node) {
                resumeObservingNode(node);
            }
        }
        else {
            resumeObservingNode();
        }
    }
    /**
     * Observe node
     */
    function observe(root, autoRemove) {
        if ( autoRemove === void 0 ) autoRemove = false;
  
        var node = addRootNode(root, autoRemove);
        startObserver(node);
        return node;
    }
    /**
     * Remove observed node
     */
    function stopObserving(root) {
        var node = findRootNode(root);
        if (node) {
            stopObserver(node);
            removeRootNode(root);
        }
    }
  
    /**
     * List of modules
     */
    var finders = [];
    /**
     * Add module
     */
    function addFinder(finder) {
        if (finders.indexOf(finder) === -1) {
            finders.push(finder);
        }
    }
    /**
     * Clean icon name: convert from string if needed and validate
     */
    function cleanIconName(name) {
        if (typeof name === 'string') {
            name = stringToIcon(name);
        }
        return name === null || !validateIcon(name) ? null : name;
    }
    /**
     * Compare customisations. Returns true if identical
     */
    function compareCustomisations(list1, list2) {
        var keys1 = Object.keys(list1);
        var keys2 = Object.keys(list2);
        if (keys1.length !== keys2.length) {
            return false;
        }
        for (var i = 0; i < keys1.length; i++) {
            var key = keys1[i];
            if (list2[key] !== list1[key]) {
                return false;
            }
        }
        return true;
    }
    /**
     * Find all placeholders
     */
    function findPlaceholders(root) {
        var results = [];
        finders.forEach(function (finder) {
            var elements = finder.find(root);
            Array.prototype.forEach.call(elements, function (item) {
                var element = item;
                if (element[elementFinderProperty] !== void 0 &&
                    element[elementFinderProperty] !== finder) {
                    // Element is assigned to a different finder
                    return;
                }
                // Get icon name
                var name = cleanIconName(finder.name(element));
                if (name === null) {
                    // Invalid name - do not assign this finder to element
                    return;
                }
                // Assign finder to element and add it to results
                element[elementFinderProperty] = finder;
                var placeholder = {
                    element: element,
                    finder: finder,
                    name: name,
                };
                results.push(placeholder);
            });
        });
        // Find all modified SVG
        var elements = root.querySelectorAll('svg.iconify');
        Array.prototype.forEach.call(elements, function (item) {
            var element = item;
            var finder = element[elementFinderProperty];
            var data = element[elementDataProperty];
            if (!finder || !data) {
                return;
            }
            // Get icon name
            var name = cleanIconName(finder.name(element));
            if (name === null) {
                // Invalid name
                return;
            }
            var updated = false;
            var customisations;
            if (name.prefix !== data.name.prefix || name.name !== data.name.name) {
                updated = true;
            }
            else {
                customisations = finder.customisations(element);
                if (!compareCustomisations(data.customisations, customisations)) {
                    updated = true;
                }
            }
            // Add item to results
            if (updated) {
                var placeholder = {
                    element: element,
                    finder: finder,
                    name: name,
                    customisations: customisations,
                };
                results.push(placeholder);
            }
        });
        return results;
    }
  
    /**
     * Flag to avoid scanning DOM too often
     */
    var scanQueued = false;
    /**
     * Icons have been loaded
     */
    function checkPendingIcons() {
        if (!scanQueued) {
            scanQueued = true;
            setTimeout(function () {
                if (scanQueued) {
                    scanQueued = false;
                    scanDOM();
                }
            });
        }
    }
    /**
     * Compare Icon objects. Returns true if icons are identical.
     *
     * Note: null means icon is invalid, so null to null comparison = false.
     */
    var compareIcons = function (icon1, icon2) {
        return (icon1 !== null &&
            icon2 !== null &&
            icon1.name === icon2.name &&
            icon1.prefix === icon2.prefix);
    };
    /**
     * Scan node for placeholders
     */
    function scanElement(root) {
        // Add temporary node
        var node = findRootNode(root);
        if (!node) {
            scanDOM({
                node: root,
                temporary: true,
            }, true);
        }
        else {
            scanDOM(node);
        }
    }
    /**
     * Scan DOM for placeholders
     */
    function scanDOM(node, addTempNode) {
        if ( addTempNode === void 0 ) addTempNode = false;
  
        scanQueued = false;
        // List of icons to load: [provider][prefix][name] = boolean
        var iconsToLoad = Object.create(null);
        // Get placeholders
        (node ? [node] : listRootNodes()).forEach(function (node) {
            var root = typeof node.node === 'function' ? node.node() : node.node;
            if (!root || !root.querySelectorAll) {
                return;
            }
            // Track placeholders
            var hasPlaceholders = false;
            // Observer
            var paused = false;
            // Find placeholders
            findPlaceholders(root).forEach(function (item) {
                var element = item.element;
                var iconName = item.name;
                var provider = iconName.provider;
                var prefix = iconName.prefix;
                var name = iconName.name;
                var data = element[elementDataProperty];
                // Icon has not been updated since last scan
                if (data !== void 0 && compareIcons(data.name, iconName)) {
                    // Icon name was not changed and data is set - quickly return if icon is missing or still loading
                    switch (data.status) {
                        case 'missing':
                            return;
                        case 'loading':
                            if (isPending({
                                provider: provider,
                                prefix: prefix,
                                name: name,
                            })) {
                                // Pending
                                hasPlaceholders = true;
                                return;
                            }
                    }
                }
                // Check icon
                var storage = getStorage(provider, prefix);
                if (storage.icons[name] !== void 0) {
                    // Icon exists - pause observer before replacing placeholder
                    if (!paused && node.observer) {
                        pauseObservingNode(node);
                        paused = true;
                    }
                    // Get customisations
                    var customisations = item.customisations !== void 0
                        ? item.customisations
                        : item.finder.customisations(element);
                    // Render icon
                    renderIconInPlaceholder(item, customisations, getIconFromStorage(storage, name));
                    return;
                }
                if (storage.missing[name]) {
                    // Mark as missing
                    data = {
                        name: iconName,
                        status: 'missing',
                        customisations: {},
                    };
                    element[elementDataProperty] = data;
                    return;
                }
                if (!isPending({ provider: provider, prefix: prefix, name: name })) {
                    // Add icon to loading queue
                    if (iconsToLoad[provider] === void 0) {
                        iconsToLoad[provider] = Object.create(null);
                    }
                    var providerIconsToLoad = iconsToLoad[provider];
                    if (providerIconsToLoad[prefix] === void 0) {
                        providerIconsToLoad[prefix] = Object.create(null);
                    }
                    providerIconsToLoad[prefix][name] = true;
                }
                // Mark as loading
                data = {
                    name: iconName,
                    status: 'loading',
                    customisations: {},
                };
                element[elementDataProperty] = data;
                hasPlaceholders = true;
            });
            // Node stuff
            if (node.temporary && !hasPlaceholders) {
                // Remove temporary node
                stopObserving(root);
            }
            else if (addTempNode && hasPlaceholders) {
                // Add new temporary node
                observe(root, true);
            }
            else if (paused && node.observer) {
                // Resume observer
                resumeObservingNode(node);
            }
        });
        // Load icons
        Object.keys(iconsToLoad).forEach(function (provider) {
            var providerIconsToLoad = iconsToLoad[provider];
            Object.keys(providerIconsToLoad).forEach(function (prefix) {
                loadIcons(Object.keys(providerIconsToLoad[prefix]).map(function (name) {
                    var icon = {
                        provider: provider,
                        prefix: prefix,
                        name: name,
                    };
                    return icon;
                }), checkPendingIcons);
            });
        });
    }
  
    // src/customisations/rotate.ts
    function rotateFromString(value, defaultValue) {
      if ( defaultValue === void 0 ) defaultValue = 0;
  
      var units = value.replace(/^-?[0-9.]*/, "");
      function cleanup(value2) {
        while (value2 < 0) {
          value2 += 4;
        }
        return value2 % 4;
      }
      if (units === "") {
        var num = parseInt(value);
        return isNaN(num) ? 0 : cleanup(num);
      } else if (units !== value) {
        var split = 0;
        switch (units) {
          case "%":
            split = 25;
            break;
          case "deg":
            split = 90;
        }
        if (split) {
          var num$1 = parseFloat(value.slice(0, value.length - units.length));
          if (isNaN(num$1)) {
            return 0;
          }
          num$1 = num$1 / split;
          return num$1 % 1 === 0 ? cleanup(num$1) : 0;
        }
      }
      return defaultValue;
    }
  
    // src/customisations/shorthand.ts
    var separator = /[\s,]+/;
    function flipFromString(custom, flip) {
      flip.split(separator).forEach(function (str) {
        var value = str.trim();
        switch (value) {
          case "horizontal":
            custom.hFlip = true;
            break;
          case "vertical":
            custom.vFlip = true;
            break;
        }
      });
    }
    function alignmentFromString(custom, align) {
      align.split(separator).forEach(function (str) {
        var value = str.trim();
        switch (value) {
          case "left":
          case "center":
          case "right":
            custom.hAlign = value;
            break;
          case "top":
          case "middle":
          case "bottom":
            custom.vAlign = value;
            break;
          case "slice":
          case "crop":
            custom.slice = true;
            break;
          case "meet":
            custom.slice = false;
        }
      });
    }
  
    /**
     * Check if attribute exists
     */
    function hasAttribute(element, key) {
        return element.hasAttribute(key);
    }
    /**
     * Get attribute value
     */
    function getAttribute(element, key) {
        return element.getAttribute(key);
    }
    /**
     * Get attribute value
     */
    function getBooleanAttribute(element, key) {
        var value = element.getAttribute(key);
        if (value === key || value === 'true') {
            return true;
        }
        if (value === '' || value === 'false') {
            return false;
        }
        return null;
    }
    /**
     * Boolean attributes
     */
    var booleanAttributes = [
        'inline',
        'hFlip',
        'vFlip' ];
    /**
     * String attributes
     */
    var stringAttributes = [
        'width',
        'height' ];
    /**
     * Class names
     */
    var mainClass = 'iconify';
    var inlineClass = 'iconify-inline';
    /**
     * Selector combining class names and tags
     */
    var selector = 'i.' +
        mainClass +
        ', span.' +
        mainClass +
        ', i.' +
        inlineClass +
        ', span.' +
        inlineClass;
    /**
     * Export finder for:
     *  <span class="iconify" />
     *  <i class="iconify" />
     *  <span class="iconify-inline" />
     *  <i class="iconify-inline" />
     */
    var finder = {
        /**
         * Find all elements
         */
        find: function (root) { return root.querySelectorAll(selector); },
        /**
         * Get icon name from element
         */
        name: function (element) {
            if (hasAttribute(element, 'data-icon')) {
                return getAttribute(element, 'data-icon');
            }
            return null;
        },
        /**
         * Get customisations list from element
         */
        customisations: function (element, defaultValues) {
            if ( defaultValues === void 0 ) defaultValues = {
            inline: false,
        };
  
            var result = defaultValues;
            // Check class list for inline class
            var className = element.getAttribute('class');
            var classList = className ? className.split(/\s+/) : [];
            if (classList.indexOf(inlineClass) !== -1) {
                result.inline = true;
            }
            // Rotation
            if (hasAttribute(element, 'data-rotate')) {
                var value = rotateFromString(getAttribute(element, 'data-rotate'));
                if (value) {
                    result.rotate = value;
                }
            }
            // Shorthand attributes
            if (hasAttribute(element, 'data-flip')) {
                flipFromString(result, getAttribute(element, 'data-flip'));
            }
            if (hasAttribute(element, 'data-align')) {
                alignmentFromString(result, getAttribute(element, 'data-align'));
            }
            // Boolean attributes
            booleanAttributes.forEach(function (attr) {
                if (hasAttribute(element, 'data-' + attr)) {
                    var value = getBooleanAttribute(element, 'data-' + attr);
                    if (typeof value === 'boolean') {
                        result[attr] = value;
                    }
                }
            });
            // String attributes
            stringAttributes.forEach(function (attr) {
                if (hasAttribute(element, 'data-' + attr)) {
                    var value = getAttribute(element, 'data-' + attr);
                    if (value !== '') {
                        result[attr] = value;
                    }
                }
            });
            return result;
        },
        /**
         * Filter classes
         */
        classFilter: function (classList) {
            var result = [];
            classList.forEach(function (className) {
                if (className !== 'iconify' &&
                    className !== '' &&
                    className.slice(0, 9) !== 'iconify--') {
                    result.push(className);
                }
            });
            return result;
        },
    };
  
    // import { finder as iconifyIconFinder } from './finders/iconify-icon';
    /**
     * Generate icon
     */
    function generateIcon(name, customisations, returnString) {
        // Get icon data
        var iconData = getIconData(name);
        if (!iconData) {
            return null;
        }
        // Split name
        var iconName = stringToIcon(name);
        // Clean up customisations
        var changes = mergeCustomisations(defaults, typeof customisations === 'object' ? customisations : {});
        // Get data
        return renderIconInPlaceholder({
            name: iconName,
        }, changes, iconData, returnString);
    }
    /**
     * Get version
     */
    function getVersion() {
        return '2.1.0';
    }
    /**
     * Generate SVG element
     */
    function renderSVG(name, customisations) {
        return generateIcon(name, customisations, false);
    }
    /**
     * Generate SVG as string
     */
    function renderHTML(name, customisations) {
        return generateIcon(name, customisations, true);
    }
    /**
     * Get rendered icon as object that can be used to create SVG (use replaceIDs on body)
     */
    function renderIcon(name, customisations) {
        // Get icon data
        var iconData = getIconData(name);
        if (!iconData) {
            return null;
        }
        // Clean up customisations
        var changes = mergeCustomisations(defaults, typeof customisations === 'object' ? customisations : {});
        // Get data
        return iconToSVG(iconData, changes);
    }
    /**
     * Scan DOM
     */
    function scan(root) {
        if (root) {
            scanElement(root);
        }
        else {
            scanDOM();
        }
    }
    /**
     * Initialise stuff
     */
    if (typeof document !== 'undefined' && typeof window !== 'undefined') {
        // Add document.body node
        addBodyNode();
        // Add finder modules
        // addFinder(iconifyIconFinder);
        addFinder(finder);
        var _window$1 = window;
        // Load icons from global "IconifyPreload"
        if (_window$1.IconifyPreload !== void 0) {
            var preload = _window$1.IconifyPreload;
            var err$1 = 'Invalid IconifyPreload syntax.';
            if (typeof preload === 'object' && preload !== null) {
                (preload instanceof Array ? preload : [preload]).forEach(function (item) {
                    try {
                        if (
                        // Check if item is an object and not null/array
                        typeof item !== 'object' ||
                            item === null ||
                            item instanceof Array ||
                            // Check for 'icons' and 'prefix'
                            typeof item.icons !== 'object' ||
                            typeof item.prefix !== 'string' ||
                            // Add icon set
                            !addCollection(item)) {
                            console.error(err$1);
                        }
                    }
                    catch (e) {
                        console.error(err$1);
                    }
                });
            }
        }
        // Load observer and scan DOM on next tick
        setTimeout(function () {
            initObserver(scanDOM);
            scanDOM();
        });
    }
  
    /**
     * Enable cache
     */
    function enableCache(storage, enable) {
        toggleBrowserCache(storage, enable !== false);
    }
    /**
     * Disable cache
     */
    function disableCache(storage) {
        toggleBrowserCache(storage, true);
    }
    /**
     * Initialise stuff
     */
    // Set API module
    setAPIModule('', getFetch() ? fetchAPIModule : jsonpAPIModule);
    /**
     * Function to enable node-fetch for getting icons on server side
     */
    function setFetch(nodeFetch) {
        setFetch$1(nodeFetch);
        setAPIModule('', fetchAPIModule);
    }
    /**
     * Browser stuff
     */
    if (typeof document !== 'undefined' && typeof window !== 'undefined') {
        // Set cache and load existing cache
        cache.store = storeCache;
        loadCache();
        var _window = window;
        // Set API from global "IconifyProviders"
        if (_window.IconifyProviders !== void 0) {
            var providers = _window.IconifyProviders;
            if (typeof providers === 'object' && providers !== null) {
                for (var key in providers) {
                    var err = 'IconifyProviders[' + key + '] is invalid.';
                    try {
                        var value = providers[key];
                        if (typeof value !== 'object' ||
                            !value ||
                            value.resources === void 0) {
                            continue;
                        }
                        if (!addAPIProvider(key, value)) {
                            console.error(err);
                        }
                    }
                    catch (e) {
                        console.error(err);
                    }
                }
            }
        }
    }
    /**
     * Internal API
     */
    var _api = {
        getAPIConfig: getAPIConfig,
        setAPIModule: setAPIModule,
        sendAPIQuery: sendAPIQuery,
        setFetch: setFetch,
        listAPIProviders: listAPIProviders,
        mergeParams: mergeParams,
    };
    /**
     * Global variable
     */
    var Iconify = {
        // IconifyAPIInternalFunctions
        _api: _api,
        // IconifyAPIFunctions
        addAPIProvider: addAPIProvider,
        loadIcons: loadIcons,
        // IconifyStorageFunctions
        iconExists: iconExists,
        getIcon: getIcon,
        listIcons: listIcons,
        addIcon: addIcon,
        addCollection: addCollection,
        // IconifyBuilderFunctions
        replaceIDs: replaceIDs,
        calculateSize: calculateSize,
        buildIcon: buildIcon,
        // IconifyCommonFunctions
        getVersion: getVersion,
        renderSVG: renderSVG,
        renderHTML: renderHTML,
        renderIcon: renderIcon,
        scan: scan,
        observe: observe,
        stopObserving: stopObserving,
        pauseObserver: pauseObserver,
        resumeObserver: resumeObserver,
        // IconifyBrowserCacheFunctions
        enableCache: enableCache,
        disableCache: disableCache,
    };
  
    exports._api = _api;
    exports.addAPIProvider = addAPIProvider;
    exports.addCollection = addCollection;
    exports.addIcon = addIcon;
    exports.buildIcon = buildIcon;
    exports.calculateSize = calculateSize;
    exports["default"] = Iconify;
    exports.disableCache = disableCache;
    exports.enableCache = enableCache;
    exports.getIcon = getIcon;
    exports.getVersion = getVersion;
    exports.iconExists = iconExists;
    exports.listIcons = listIcons;
    exports.loadIcons = loadIcons;
    exports.observe = observe;
    exports.pauseObserver = pauseObserver;
    exports.renderHTML = renderHTML;
    exports.renderIcon = renderIcon;
    exports.renderSVG = renderSVG;
    exports.replaceIDs = replaceIDs;
    exports.resumeObserver = resumeObserver;
    exports.scan = scan;
    exports.stopObserving = stopObserving;
  
    Object.defineProperty(exports, '__esModule', { value: true });
  
    return exports;
  
  })({});
  
  // Export as ES module
  if (typeof exports === 'object') {
      try {
          exports.__esModule = true;
          exports.default = Iconify;
          for (var key in Iconify) {
              exports[key] = Iconify[key];
          }
      } catch (err) {
      }
  }
  
  
  // Export to window or web worker
  try {
      if (self.Iconify === void 0) {
          self.Iconify = Iconify;
      }
  } catch (err) {
  }
/*!
 * Masonry PACKAGED v4.2.2
 * Cascading grid layout library
 * https://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

/**
 * Bridget makes jQuery widgets
 * v2.0.1
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {
  // universal module definition
  /*jshint strict: false */ /* globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'jquery-bridget/jquery-bridget',[ 'jquery' ], function( jQuery ) {
      return factory( window, jQuery );
    });
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      window,
      require('jquery')
    );
  } else {
    // browser global
    window.jQueryBridget = factory(
      window,
      window.jQuery
    );
  }

}( window, function factory( window, jQuery ) {
'use strict';

// ----- utils ----- //

var arraySlice = Array.prototype.slice;

// helper function for logging errors
// $.error breaks jQuery chaining
var console = window.console;
var logError = typeof console == 'undefined' ? function() {} :
  function( message ) {
    console.error( message );
  };

// ----- jQueryBridget ----- //

function jQueryBridget( namespace, PluginClass, $ ) {
  $ = $ || jQuery || window.jQuery;
  if ( !$ ) {
    return;
  }

  // add option method -> $().plugin('option', {...})
  if ( !PluginClass.prototype.option ) {
    // option setter
    PluginClass.prototype.option = function( opts ) {
      // bail out if not an object
      if ( !$.isPlainObject( opts ) ){
        return;
      }
      this.options = $.extend( true, this.options, opts );
    };
  }

  // make jQuery plugin
  $.fn[ namespace ] = function( arg0 /*, arg1 */ ) {
    if ( typeof arg0 == 'string' ) {
      // method call $().plugin( 'methodName', { options } )
      // shift arguments by 1
      var args = arraySlice.call( arguments, 1 );
      return methodCall( this, arg0, args );
    }
    // just $().plugin({ options })
    plainCall( this, arg0 );
    return this;
  };

  // $().plugin('methodName')
  function methodCall( $elems, methodName, args ) {
    var returnValue;
    var pluginMethodStr = '$().' + namespace + '("' + methodName + '")';

    $elems.each( function( i, elem ) {
      // get instance
      var instance = $.data( elem, namespace );
      if ( !instance ) {
        logError( namespace + ' not initialized. Cannot call methods, i.e. ' +
          pluginMethodStr );
        return;
      }

      var method = instance[ methodName ];
      if ( !method || methodName.charAt(0) == '_' ) {
        logError( pluginMethodStr + ' is not a valid method' );
        return;
      }

      // apply method, get return value
      var value = method.apply( instance, args );
      // set return value if value is returned, use only first value
      returnValue = returnValue === undefined ? value : returnValue;
    });

    return returnValue !== undefined ? returnValue : $elems;
  }

  function plainCall( $elems, options ) {
    $elems.each( function( i, elem ) {
      var instance = $.data( elem, namespace );
      if ( instance ) {
        // set options & init
        instance.option( options );
        instance._init();
      } else {
        // initialize new instance
        instance = new PluginClass( elem, options );
        $.data( elem, namespace, instance );
      }
    });
  }

  updateJQuery( $ );

}

// ----- updateJQuery ----- //

// set $.bridget for v1 backwards compatibility
function updateJQuery( $ ) {
  if ( !$ || ( $ && $.bridget ) ) {
    return;
  }
  $.bridget = jQueryBridget;
}

updateJQuery( jQuery || window.jQuery );

// -----  ----- //

return jQueryBridget;

}));

/**
 * EvEmitter v1.1.0
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

( function( global, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, window */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( 'ev-emitter/ev-emitter',factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory();
  } else {
    // Browser globals
    global.EvEmitter = factory();
  }

}( typeof window != 'undefined' ? window : this, function() {



function EvEmitter() {}

var proto = EvEmitter.prototype;

proto.on = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // set events hash
  var events = this._events = this._events || {};
  // set listeners array
  var listeners = events[ eventName ] = events[ eventName ] || [];
  // only add once
  if ( listeners.indexOf( listener ) == -1 ) {
    listeners.push( listener );
  }

  return this;
};

proto.once = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // add event
  this.on( eventName, listener );
  // set once flag
  // set onceEvents hash
  var onceEvents = this._onceEvents = this._onceEvents || {};
  // set onceListeners object
  var onceListeners = onceEvents[ eventName ] = onceEvents[ eventName ] || {};
  // set flag
  onceListeners[ listener ] = true;

  return this;
};

proto.off = function( eventName, listener ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  var index = listeners.indexOf( listener );
  if ( index != -1 ) {
    listeners.splice( index, 1 );
  }

  return this;
};

proto.emitEvent = function( eventName, args ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  // copy over to avoid interference if .off() in listener
  listeners = listeners.slice(0);
  args = args || [];
  // once stuff
  var onceListeners = this._onceEvents && this._onceEvents[ eventName ];

  for ( var i=0; i < listeners.length; i++ ) {
    var listener = listeners[i]
    var isOnce = onceListeners && onceListeners[ listener ];
    if ( isOnce ) {
      // remove listener
      // remove before trigger to prevent recursion
      this.off( eventName, listener );
      // unset once flag
      delete onceListeners[ listener ];
    }
    // trigger listener
    listener.apply( this, args );
  }

  return this;
};

proto.allOff = function() {
  delete this._events;
  delete this._onceEvents;
};

return EvEmitter;

}));

/*!
 * getSize v2.0.3
 * measure size of elements
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */
/* globals console: false */

( function( window, factory ) {
  /* jshint strict: false */ /* globals define, module */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'get-size/get-size',factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory();
  } else {
    // browser global
    window.getSize = factory();
  }

})( window, function factory() {
'use strict';

// -------------------------- helpers -------------------------- //

// get a number from a string, not a percentage
function getStyleSize( value ) {
  var num = parseFloat( value );
  // not a percent like '100%', and a number
  var isValid = value.indexOf('%') == -1 && !isNaN( num );
  return isValid && num;
}

function noop() {}

var logError = typeof console == 'undefined' ? noop :
  function( message ) {
    console.error( message );
  };

// -------------------------- measurements -------------------------- //

var measurements = [
  'paddingLeft',
  'paddingRight',
  'paddingTop',
  'paddingBottom',
  'marginLeft',
  'marginRight',
  'marginTop',
  'marginBottom',
  'borderLeftWidth',
  'borderRightWidth',
  'borderTopWidth',
  'borderBottomWidth'
];

var measurementsLength = measurements.length;

function getZeroSize() {
  var size = {
    width: 0,
    height: 0,
    innerWidth: 0,
    innerHeight: 0,
    outerWidth: 0,
    outerHeight: 0
  };
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    size[ measurement ] = 0;
  }
  return size;
}

// -------------------------- getStyle -------------------------- //

/**
 * getStyle, get style of element, check for Firefox bug
 * https://bugzilla.mozilla.org/show_bug.cgi?id=548397
 */
function getStyle( elem ) {
  var style = getComputedStyle( elem );
  if ( !style ) {
    logError( 'Style returned ' + style +
      '. Are you running this code in a hidden iframe on Firefox? ' +
      'See https://bit.ly/getsizebug1' );
  }
  return style;
}

// -------------------------- setup -------------------------- //

var isSetup = false;

var isBoxSizeOuter;

/**
 * setup
 * check isBoxSizerOuter
 * do on first getSize() rather than on page load for Firefox bug
 */
function setup() {
  // setup once
  if ( isSetup ) {
    return;
  }
  isSetup = true;

  // -------------------------- box sizing -------------------------- //

  /**
   * Chrome & Safari measure the outer-width on style.width on border-box elems
   * IE11 & Firefox<29 measures the inner-width
   */
  var div = document.createElement('div');
  div.style.width = '200px';
  div.style.padding = '1px 2px 3px 4px';
  div.style.borderStyle = 'solid';
  div.style.borderWidth = '1px 2px 3px 4px';
  div.style.boxSizing = 'border-box';

  var body = document.body || document.documentElement;
  body.appendChild( div );
  var style = getStyle( div );
  // round value for browser zoom. desandro/masonry#928
  isBoxSizeOuter = Math.round( getStyleSize( style.width ) ) == 200;
  getSize.isBoxSizeOuter = isBoxSizeOuter;

  body.removeChild( div );
}

// -------------------------- getSize -------------------------- //

function getSize( elem ) {
  setup();

  // use querySeletor if elem is string
  if ( typeof elem == 'string' ) {
    elem = document.querySelector( elem );
  }

  // do not proceed on non-objects
  if ( !elem || typeof elem != 'object' || !elem.nodeType ) {
    return;
  }

  var style = getStyle( elem );

  // if hidden, everything is 0
  if ( style.display == 'none' ) {
    return getZeroSize();
  }

  var size = {};
  size.width = elem.offsetWidth;
  size.height = elem.offsetHeight;

  var isBorderBox = size.isBorderBox = style.boxSizing == 'border-box';

  // get all measurements
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    var value = style[ measurement ];
    var num = parseFloat( value );
    // any 'auto', 'medium' value will be 0
    size[ measurement ] = !isNaN( num ) ? num : 0;
  }

  var paddingWidth = size.paddingLeft + size.paddingRight;
  var paddingHeight = size.paddingTop + size.paddingBottom;
  var marginWidth = size.marginLeft + size.marginRight;
  var marginHeight = size.marginTop + size.marginBottom;
  var borderWidth = size.borderLeftWidth + size.borderRightWidth;
  var borderHeight = size.borderTopWidth + size.borderBottomWidth;

  var isBorderBoxSizeOuter = isBorderBox && isBoxSizeOuter;

  // overwrite width and height if we can get it from style
  var styleWidth = getStyleSize( style.width );
  if ( styleWidth !== false ) {
    size.width = styleWidth +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingWidth + borderWidth );
  }

  var styleHeight = getStyleSize( style.height );
  if ( styleHeight !== false ) {
    size.height = styleHeight +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingHeight + borderHeight );
  }

  size.innerWidth = size.width - ( paddingWidth + borderWidth );
  size.innerHeight = size.height - ( paddingHeight + borderHeight );

  size.outerWidth = size.width + marginWidth;
  size.outerHeight = size.height + marginHeight;

  return size;
}

return getSize;

});

/**
 * matchesSelector v2.0.2
 * matchesSelector( element, '.selector' )
 * MIT license
 */

/*jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {
  /*global define: false, module: false */
  'use strict';
  // universal module definition
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'desandro-matches-selector/matches-selector',factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory();
  } else {
    // browser global
    window.matchesSelector = factory();
  }

}( window, function factory() {
  'use strict';

  var matchesMethod = ( function() {
    var ElemProto = window.Element.prototype;
    // check for the standard method name first
    if ( ElemProto.matches ) {
      return 'matches';
    }
    // check un-prefixed
    if ( ElemProto.matchesSelector ) {
      return 'matchesSelector';
    }
    // check vendor prefixes
    var prefixes = [ 'webkit', 'moz', 'ms', 'o' ];

    for ( var i=0; i < prefixes.length; i++ ) {
      var prefix = prefixes[i];
      var method = prefix + 'MatchesSelector';
      if ( ElemProto[ method ] ) {
        return method;
      }
    }
  })();

  return function matchesSelector( elem, selector ) {
    return elem[ matchesMethod ]( selector );
  };

}));

/**
 * Fizzy UI utils v2.0.7
 * MIT license
 */

/*jshint browser: true, undef: true, unused: true, strict: true */

( function( window, factory ) {
  // universal module definition
  /*jshint strict: false */ /*globals define, module, require */

  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'fizzy-ui-utils/utils',[
      'desandro-matches-selector/matches-selector'
    ], function( matchesSelector ) {
      return factory( window, matchesSelector );
    });
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      window,
      require('desandro-matches-selector')
    );
  } else {
    // browser global
    window.fizzyUIUtils = factory(
      window,
      window.matchesSelector
    );
  }

}( window, function factory( window, matchesSelector ) {



var utils = {};

// ----- extend ----- //

// extends objects
utils.extend = function( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
};

// ----- modulo ----- //

utils.modulo = function( num, div ) {
  return ( ( num % div ) + div ) % div;
};

// ----- makeArray ----- //

var arraySlice = Array.prototype.slice;

// turn element or nodeList into an array
utils.makeArray = function( obj ) {
  if ( Array.isArray( obj ) ) {
    // use object if already an array
    return obj;
  }
  // return empty array if undefined or null. #6
  if ( obj === null || obj === undefined ) {
    return [];
  }

  var isArrayLike = typeof obj == 'object' && typeof obj.length == 'number';
  if ( isArrayLike ) {
    // convert nodeList to array
    return arraySlice.call( obj );
  }

  // array of single index
  return [ obj ];
};

// ----- removeFrom ----- //

utils.removeFrom = function( ary, obj ) {
  var index = ary.indexOf( obj );
  if ( index != -1 ) {
    ary.splice( index, 1 );
  }
};

// ----- getParent ----- //

utils.getParent = function( elem, selector ) {
  while ( elem.parentNode && elem != document.body ) {
    elem = elem.parentNode;
    if ( matchesSelector( elem, selector ) ) {
      return elem;
    }
  }
};

// ----- getQueryElement ----- //

// use element as selector string
utils.getQueryElement = function( elem ) {
  if ( typeof elem == 'string' ) {
    return document.querySelector( elem );
  }
  return elem;
};

// ----- handleEvent ----- //

// enable .ontype to trigger from .addEventListener( elem, 'type' )
utils.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

// ----- filterFindElements ----- //

utils.filterFindElements = function( elems, selector ) {
  // make array of elems
  elems = utils.makeArray( elems );
  var ffElems = [];

  elems.forEach( function( elem ) {
    // check that elem is an actual element
    if ( !( elem instanceof HTMLElement ) ) {
      return;
    }
    // add elem if no selector
    if ( !selector ) {
      ffElems.push( elem );
      return;
    }
    // filter & find items if we have a selector
    // filter
    if ( matchesSelector( elem, selector ) ) {
      ffElems.push( elem );
    }
    // find children
    var childElems = elem.querySelectorAll( selector );
    // concat childElems to filterFound array
    for ( var i=0; i < childElems.length; i++ ) {
      ffElems.push( childElems[i] );
    }
  });

  return ffElems;
};

// ----- debounceMethod ----- //

utils.debounceMethod = function( _class, methodName, threshold ) {
  threshold = threshold || 100;
  // original method
  var method = _class.prototype[ methodName ];
  var timeoutName = methodName + 'Timeout';

  _class.prototype[ methodName ] = function() {
    var timeout = this[ timeoutName ];
    clearTimeout( timeout );

    var args = arguments;
    var _this = this;
    this[ timeoutName ] = setTimeout( function() {
      method.apply( _this, args );
      delete _this[ timeoutName ];
    }, threshold );
  };
};

// ----- docReady ----- //

utils.docReady = function( callback ) {
  var readyState = document.readyState;
  if ( readyState == 'complete' || readyState == 'interactive' ) {
    // do async to allow for other scripts to run. metafizzy/flickity#441
    setTimeout( callback );
  } else {
    document.addEventListener( 'DOMContentLoaded', callback );
  }
};

// ----- htmlInit ----- //

// http://jamesroberts.name/blog/2010/02/22/string-functions-for-javascript-trim-to-camel-case-to-dashed-and-to-underscore/
utils.toDashed = function( str ) {
  return str.replace( /(.)([A-Z])/g, function( match, $1, $2 ) {
    return $1 + '-' + $2;
  }).toLowerCase();
};

var console = window.console;
/**
 * allow user to initialize classes via [data-namespace] or .js-namespace class
 * htmlInit( Widget, 'widgetName' )
 * options are parsed from data-namespace-options
 */
utils.htmlInit = function( WidgetClass, namespace ) {
  utils.docReady( function() {
    var dashedNamespace = utils.toDashed( namespace );
    var dataAttr = 'data-' + dashedNamespace;
    var dataAttrElems = document.querySelectorAll( '[' + dataAttr + ']' );
    var jsDashElems = document.querySelectorAll( '.js-' + dashedNamespace );
    var elems = utils.makeArray( dataAttrElems )
      .concat( utils.makeArray( jsDashElems ) );
    var dataOptionsAttr = dataAttr + '-options';
    var jQuery = window.jQuery;

    elems.forEach( function( elem ) {
      var attr = elem.getAttribute( dataAttr ) ||
        elem.getAttribute( dataOptionsAttr );
      var options;
      try {
        options = attr && JSON.parse( attr );
      } catch ( error ) {
        // log error, do not initialize
        if ( console ) {
          console.error( 'Error parsing ' + dataAttr + ' on ' + elem.className +
          ': ' + error );
        }
        return;
      }
      // initialize
      var instance = new WidgetClass( elem, options );
      // make available via $().data('namespace')
      if ( jQuery ) {
        jQuery.data( elem, namespace, instance );
      }
    });

  });
};

// -----  ----- //

return utils;

}));

/**
 * Outlayer Item
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( 'outlayer/item',[
        'ev-emitter/ev-emitter',
        'get-size/get-size'
      ],
      factory
    );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory(
      require('ev-emitter'),
      require('get-size')
    );
  } else {
    // browser global
    window.Outlayer = {};
    window.Outlayer.Item = factory(
      window.EvEmitter,
      window.getSize
    );
  }

}( window, function factory( EvEmitter, getSize ) {
'use strict';

// ----- helpers ----- //

function isEmptyObj( obj ) {
  for ( var prop in obj ) {
    return false;
  }
  prop = null;
  return true;
}

// -------------------------- CSS3 support -------------------------- //


var docElemStyle = document.documentElement.style;

var transitionProperty = typeof docElemStyle.transition == 'string' ?
  'transition' : 'WebkitTransition';
var transformProperty = typeof docElemStyle.transform == 'string' ?
  'transform' : 'WebkitTransform';

var transitionEndEvent = {
  WebkitTransition: 'webkitTransitionEnd',
  transition: 'transitionend'
}[ transitionProperty ];

// cache all vendor properties that could have vendor prefix
var vendorProperties = {
  transform: transformProperty,
  transition: transitionProperty,
  transitionDuration: transitionProperty + 'Duration',
  transitionProperty: transitionProperty + 'Property',
  transitionDelay: transitionProperty + 'Delay'
};

// -------------------------- Item -------------------------- //

function Item( element, layout ) {
  if ( !element ) {
    return;
  }

  this.element = element;
  // parent layout class, i.e. Masonry, Isotope, or Packery
  this.layout = layout;
  this.position = {
    x: 0,
    y: 0
  };

  this._create();
}

// inherit EvEmitter
var proto = Item.prototype = Object.create( EvEmitter.prototype );
proto.constructor = Item;

proto._create = function() {
  // transition objects
  this._transn = {
    ingProperties: {},
    clean: {},
    onEnd: {}
  };

  this.css({
    position: 'absolute'
  });
};

// trigger specified handler for event type
proto.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

proto.getSize = function() {
  this.size = getSize( this.element );
};

/**
 * apply CSS styles to element
 * @param {Object} style
 */
proto.css = function( style ) {
  var elemStyle = this.element.style;

  for ( var prop in style ) {
    // use vendor property if available
    var supportedProp = vendorProperties[ prop ] || prop;
    elemStyle[ supportedProp ] = style[ prop ];
  }
};

 // measure position, and sets it
proto.getPosition = function() {
  var style = getComputedStyle( this.element );
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');
  var xValue = style[ isOriginLeft ? 'left' : 'right' ];
  var yValue = style[ isOriginTop ? 'top' : 'bottom' ];
  var x = parseFloat( xValue );
  var y = parseFloat( yValue );
  // convert percent to pixels
  var layoutSize = this.layout.size;
  if ( xValue.indexOf('%') != -1 ) {
    x = ( x / 100 ) * layoutSize.width;
  }
  if ( yValue.indexOf('%') != -1 ) {
    y = ( y / 100 ) * layoutSize.height;
  }
  // clean up 'auto' or other non-integer values
  x = isNaN( x ) ? 0 : x;
  y = isNaN( y ) ? 0 : y;
  // remove padding from measurement
  x -= isOriginLeft ? layoutSize.paddingLeft : layoutSize.paddingRight;
  y -= isOriginTop ? layoutSize.paddingTop : layoutSize.paddingBottom;

  this.position.x = x;
  this.position.y = y;
};

// set settled position, apply padding
proto.layoutPosition = function() {
  var layoutSize = this.layout.size;
  var style = {};
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');

  // x
  var xPadding = isOriginLeft ? 'paddingLeft' : 'paddingRight';
  var xProperty = isOriginLeft ? 'left' : 'right';
  var xResetProperty = isOriginLeft ? 'right' : 'left';

  var x = this.position.x + layoutSize[ xPadding ];
  // set in percentage or pixels
  style[ xProperty ] = this.getXValue( x );
  // reset other property
  style[ xResetProperty ] = '';

  // y
  var yPadding = isOriginTop ? 'paddingTop' : 'paddingBottom';
  var yProperty = isOriginTop ? 'top' : 'bottom';
  var yResetProperty = isOriginTop ? 'bottom' : 'top';

  var y = this.position.y + layoutSize[ yPadding ];
  // set in percentage or pixels
  style[ yProperty ] = this.getYValue( y );
  // reset other property
  style[ yResetProperty ] = '';

  this.css( style );
  this.emitEvent( 'layout', [ this ] );
};

proto.getXValue = function( x ) {
  var isHorizontal = this.layout._getOption('horizontal');
  return this.layout.options.percentPosition && !isHorizontal ?
    ( ( x / this.layout.size.width ) * 100 ) + '%' : x + 'px';
};

proto.getYValue = function( y ) {
  var isHorizontal = this.layout._getOption('horizontal');
  return this.layout.options.percentPosition && isHorizontal ?
    ( ( y / this.layout.size.height ) * 100 ) + '%' : y + 'px';
};

proto._transitionTo = function( x, y ) {
  this.getPosition();
  // get current x & y from top/left
  var curX = this.position.x;
  var curY = this.position.y;

  var didNotMove = x == this.position.x && y == this.position.y;

  // save end position
  this.setPosition( x, y );

  // if did not move and not transitioning, just go to layout
  if ( didNotMove && !this.isTransitioning ) {
    this.layoutPosition();
    return;
  }

  var transX = x - curX;
  var transY = y - curY;
  var transitionStyle = {};
  transitionStyle.transform = this.getTranslate( transX, transY );

  this.transition({
    to: transitionStyle,
    onTransitionEnd: {
      transform: this.layoutPosition
    },
    isCleaning: true
  });
};

proto.getTranslate = function( x, y ) {
  // flip cooridinates if origin on right or bottom
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');
  x = isOriginLeft ? x : -x;
  y = isOriginTop ? y : -y;
  return 'translate3d(' + x + 'px, ' + y + 'px, 0)';
};

// non transition + transform support
proto.goTo = function( x, y ) {
  this.setPosition( x, y );
  this.layoutPosition();
};

proto.moveTo = proto._transitionTo;

proto.setPosition = function( x, y ) {
  this.position.x = parseFloat( x );
  this.position.y = parseFloat( y );
};

// ----- transition ----- //

/**
 * @param {Object} style - CSS
 * @param {Function} onTransitionEnd
 */

// non transition, just trigger callback
proto._nonTransition = function( args ) {
  this.css( args.to );
  if ( args.isCleaning ) {
    this._removeStyles( args.to );
  }
  for ( var prop in args.onTransitionEnd ) {
    args.onTransitionEnd[ prop ].call( this );
  }
};

/**
 * proper transition
 * @param {Object} args - arguments
 *   @param {Object} to - style to transition to
 *   @param {Object} from - style to start transition from
 *   @param {Boolean} isCleaning - removes transition styles after transition
 *   @param {Function} onTransitionEnd - callback
 */
proto.transition = function( args ) {
  // redirect to nonTransition if no transition duration
  if ( !parseFloat( this.layout.options.transitionDuration ) ) {
    this._nonTransition( args );
    return;
  }

  var _transition = this._transn;
  // keep track of onTransitionEnd callback by css property
  for ( var prop in args.onTransitionEnd ) {
    _transition.onEnd[ prop ] = args.onTransitionEnd[ prop ];
  }
  // keep track of properties that are transitioning
  for ( prop in args.to ) {
    _transition.ingProperties[ prop ] = true;
    // keep track of properties to clean up when transition is done
    if ( args.isCleaning ) {
      _transition.clean[ prop ] = true;
    }
  }

  // set from styles
  if ( args.from ) {
    this.css( args.from );
    // force redraw. http://blog.alexmaccaw.com/css-transitions
    var h = this.element.offsetHeight;
    // hack for JSHint to hush about unused var
    h = null;
  }
  // enable transition
  this.enableTransition( args.to );
  // set styles that are transitioning
  this.css( args.to );

  this.isTransitioning = true;

};

// dash before all cap letters, including first for
// WebkitTransform => -webkit-transform
function toDashedAll( str ) {
  return str.replace( /([A-Z])/g, function( $1 ) {
    return '-' + $1.toLowerCase();
  });
}

var transitionProps = 'opacity,' + toDashedAll( transformProperty );

proto.enableTransition = function(/* style */) {
  // HACK changing transitionProperty during a transition
  // will cause transition to jump
  if ( this.isTransitioning ) {
    return;
  }

  // make `transition: foo, bar, baz` from style object
  // HACK un-comment this when enableTransition can work
  // while a transition is happening
  // var transitionValues = [];
  // for ( var prop in style ) {
  //   // dash-ify camelCased properties like WebkitTransition
  //   prop = vendorProperties[ prop ] || prop;
  //   transitionValues.push( toDashedAll( prop ) );
  // }
  // munge number to millisecond, to match stagger
  var duration = this.layout.options.transitionDuration;
  duration = typeof duration == 'number' ? duration + 'ms' : duration;
  // enable transition styles
  this.css({
    transitionProperty: transitionProps,
    transitionDuration: duration,
    transitionDelay: this.staggerDelay || 0
  });
  // listen for transition end event
  this.element.addEventListener( transitionEndEvent, this, false );
};

// ----- events ----- //

proto.onwebkitTransitionEnd = function( event ) {
  this.ontransitionend( event );
};

proto.onotransitionend = function( event ) {
  this.ontransitionend( event );
};

// properties that I munge to make my life easier
var dashedVendorProperties = {
  '-webkit-transform': 'transform'
};

proto.ontransitionend = function( event ) {
  // disregard bubbled events from children
  if ( event.target !== this.element ) {
    return;
  }
  var _transition = this._transn;
  // get property name of transitioned property, convert to prefix-free
  var propertyName = dashedVendorProperties[ event.propertyName ] || event.propertyName;

  // remove property that has completed transitioning
  delete _transition.ingProperties[ propertyName ];
  // check if any properties are still transitioning
  if ( isEmptyObj( _transition.ingProperties ) ) {
    // all properties have completed transitioning
    this.disableTransition();
  }
  // clean style
  if ( propertyName in _transition.clean ) {
    // clean up style
    this.element.style[ event.propertyName ] = '';
    delete _transition.clean[ propertyName ];
  }
  // trigger onTransitionEnd callback
  if ( propertyName in _transition.onEnd ) {
    var onTransitionEnd = _transition.onEnd[ propertyName ];
    onTransitionEnd.call( this );
    delete _transition.onEnd[ propertyName ];
  }

  this.emitEvent( 'transitionEnd', [ this ] );
};

proto.disableTransition = function() {
  this.removeTransitionStyles();
  this.element.removeEventListener( transitionEndEvent, this, false );
  this.isTransitioning = false;
};

/**
 * removes style property from element
 * @param {Object} style
**/
proto._removeStyles = function( style ) {
  // clean up transition styles
  var cleanStyle = {};
  for ( var prop in style ) {
    cleanStyle[ prop ] = '';
  }
  this.css( cleanStyle );
};

var cleanTransitionStyle = {
  transitionProperty: '',
  transitionDuration: '',
  transitionDelay: ''
};

proto.removeTransitionStyles = function() {
  // remove transition
  this.css( cleanTransitionStyle );
};

// ----- stagger ----- //

proto.stagger = function( delay ) {
  delay = isNaN( delay ) ? 0 : delay;
  this.staggerDelay = delay + 'ms';
};

// ----- show/hide/remove ----- //

// remove element from DOM
proto.removeElem = function() {
  this.element.parentNode.removeChild( this.element );
  // remove display: none
  this.css({ display: '' });
  this.emitEvent( 'remove', [ this ] );
};

proto.remove = function() {
  // just remove element if no transition support or no transition
  if ( !transitionProperty || !parseFloat( this.layout.options.transitionDuration ) ) {
    this.removeElem();
    return;
  }

  // start transition
  this.once( 'transitionEnd', function() {
    this.removeElem();
  });
  this.hide();
};

proto.reveal = function() {
  delete this.isHidden;
  // remove display: none
  this.css({ display: '' });

  var options = this.layout.options;

  var onTransitionEnd = {};
  var transitionEndProperty = this.getHideRevealTransitionEndProperty('visibleStyle');
  onTransitionEnd[ transitionEndProperty ] = this.onRevealTransitionEnd;

  this.transition({
    from: options.hiddenStyle,
    to: options.visibleStyle,
    isCleaning: true,
    onTransitionEnd: onTransitionEnd
  });
};

proto.onRevealTransitionEnd = function() {
  // check if still visible
  // during transition, item may have been hidden
  if ( !this.isHidden ) {
    this.emitEvent('reveal');
  }
};

/**
 * get style property use for hide/reveal transition end
 * @param {String} styleProperty - hiddenStyle/visibleStyle
 * @returns {String}
 */
proto.getHideRevealTransitionEndProperty = function( styleProperty ) {
  var optionStyle = this.layout.options[ styleProperty ];
  // use opacity
  if ( optionStyle.opacity ) {
    return 'opacity';
  }
  // get first property
  for ( var prop in optionStyle ) {
    return prop;
  }
};

proto.hide = function() {
  // set flag
  this.isHidden = true;
  // remove display: none
  this.css({ display: '' });

  var options = this.layout.options;

  var onTransitionEnd = {};
  var transitionEndProperty = this.getHideRevealTransitionEndProperty('hiddenStyle');
  onTransitionEnd[ transitionEndProperty ] = this.onHideTransitionEnd;

  this.transition({
    from: options.visibleStyle,
    to: options.hiddenStyle,
    // keep hidden stuff hidden
    isCleaning: true,
    onTransitionEnd: onTransitionEnd
  });
};

proto.onHideTransitionEnd = function() {
  // check if still hidden
  // during transition, item may have been un-hidden
  if ( this.isHidden ) {
    this.css({ display: 'none' });
    this.emitEvent('hide');
  }
};

proto.destroy = function() {
  this.css({
    position: '',
    left: '',
    right: '',
    top: '',
    bottom: '',
    transition: '',
    transform: ''
  });
};

return Item;

}));

/*!
 * Outlayer v2.1.1
 * the brains and guts of a layout library
 * MIT license
 */

( function( window, factory ) {
  'use strict';
  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( 'outlayer/outlayer',[
        'ev-emitter/ev-emitter',
        'get-size/get-size',
        'fizzy-ui-utils/utils',
        './item'
      ],
      function( EvEmitter, getSize, utils, Item ) {
        return factory( window, EvEmitter, getSize, utils, Item);
      }
    );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory(
      window,
      require('ev-emitter'),
      require('get-size'),
      require('fizzy-ui-utils'),
      require('./item')
    );
  } else {
    // browser global
    window.Outlayer = factory(
      window,
      window.EvEmitter,
      window.getSize,
      window.fizzyUIUtils,
      window.Outlayer.Item
    );
  }

}( window, function factory( window, EvEmitter, getSize, utils, Item ) {
'use strict';

// ----- vars ----- //

var console = window.console;
var jQuery = window.jQuery;
var noop = function() {};

// -------------------------- Outlayer -------------------------- //

// globally unique identifiers
var GUID = 0;
// internal store of all Outlayer intances
var instances = {};


/**
 * @param {Element, String} element
 * @param {Object} options
 * @constructor
 */
function Outlayer( element, options ) {
  var queryElement = utils.getQueryElement( element );
  if ( !queryElement ) {
    if ( console ) {
      console.error( 'Bad element for ' + this.constructor.namespace +
        ': ' + ( queryElement || element ) );
    }
    return;
  }
  this.element = queryElement;
  // add jQuery
  if ( jQuery ) {
    this.$element = jQuery( this.element );
  }

  // options
  this.options = utils.extend( {}, this.constructor.defaults );
  this.option( options );

  // add id for Outlayer.getFromElement
  var id = ++GUID;
  this.element.outlayerGUID = id; // expando
  instances[ id ] = this; // associate via id

  // kick it off
  this._create();

  var isInitLayout = this._getOption('initLayout');
  if ( isInitLayout ) {
    this.layout();
  }
}

// settings are for internal use only
Outlayer.namespace = 'outlayer';
Outlayer.Item = Item;

// default options
Outlayer.defaults = {
  containerStyle: {
    position: 'relative'
  },
  initLayout: true,
  originLeft: true,
  originTop: true,
  resize: true,
  resizeContainer: true,
  // item options
  transitionDuration: '0.4s',
  hiddenStyle: {
    opacity: 0,
    transform: 'scale(0.001)'
  },
  visibleStyle: {
    opacity: 1,
    transform: 'scale(1)'
  }
};

var proto = Outlayer.prototype;
// inherit EvEmitter
utils.extend( proto, EvEmitter.prototype );

/**
 * set options
 * @param {Object} opts
 */
proto.option = function( opts ) {
  utils.extend( this.options, opts );
};

/**
 * get backwards compatible option value, check old name
 */
proto._getOption = function( option ) {
  var oldOption = this.constructor.compatOptions[ option ];
  return oldOption && this.options[ oldOption ] !== undefined ?
    this.options[ oldOption ] : this.options[ option ];
};

Outlayer.compatOptions = {
  // currentName: oldName
  initLayout: 'isInitLayout',
  horizontal: 'isHorizontal',
  layoutInstant: 'isLayoutInstant',
  originLeft: 'isOriginLeft',
  originTop: 'isOriginTop',
  resize: 'isResizeBound',
  resizeContainer: 'isResizingContainer'
};

proto._create = function() {
  // get items from children
  this.reloadItems();
  // elements that affect layout, but are not laid out
  this.stamps = [];
  this.stamp( this.options.stamp );
  // set container style
  utils.extend( this.element.style, this.options.containerStyle );

  // bind resize method
  var canBindResize = this._getOption('resize');
  if ( canBindResize ) {
    this.bindResize();
  }
};

// goes through all children again and gets bricks in proper order
proto.reloadItems = function() {
  // collection of item elements
  this.items = this._itemize( this.element.children );
};


/**
 * turn elements into Outlayer.Items to be used in layout
 * @param {Array or NodeList or HTMLElement} elems
 * @returns {Array} items - collection of new Outlayer Items
 */
proto._itemize = function( elems ) {

  var itemElems = this._filterFindItemElements( elems );
  var Item = this.constructor.Item;

  // create new Outlayer Items for collection
  var items = [];
  for ( var i=0; i < itemElems.length; i++ ) {
    var elem = itemElems[i];
    var item = new Item( elem, this );
    items.push( item );
  }

  return items;
};

/**
 * get item elements to be used in layout
 * @param {Array or NodeList or HTMLElement} elems
 * @returns {Array} items - item elements
 */
proto._filterFindItemElements = function( elems ) {
  return utils.filterFindElements( elems, this.options.itemSelector );
};

/**
 * getter method for getting item elements
 * @returns {Array} elems - collection of item elements
 */
proto.getItemElements = function() {
  return this.items.map( function( item ) {
    return item.element;
  });
};

// ----- init & layout ----- //

/**
 * lays out all items
 */
proto.layout = function() {
  this._resetLayout();
  this._manageStamps();

  // don't animate first layout
  var layoutInstant = this._getOption('layoutInstant');
  var isInstant = layoutInstant !== undefined ?
    layoutInstant : !this._isLayoutInited;
  this.layoutItems( this.items, isInstant );

  // flag for initalized
  this._isLayoutInited = true;
};

// _init is alias for layout
proto._init = proto.layout;

/**
 * logic before any new layout
 */
proto._resetLayout = function() {
  this.getSize();
};


proto.getSize = function() {
  this.size = getSize( this.element );
};

/**
 * get measurement from option, for columnWidth, rowHeight, gutter
 * if option is String -> get element from selector string, & get size of element
 * if option is Element -> get size of element
 * else use option as a number
 *
 * @param {String} measurement
 * @param {String} size - width or height
 * @private
 */
proto._getMeasurement = function( measurement, size ) {
  var option = this.options[ measurement ];
  var elem;
  if ( !option ) {
    // default to 0
    this[ measurement ] = 0;
  } else {
    // use option as an element
    if ( typeof option == 'string' ) {
      elem = this.element.querySelector( option );
    } else if ( option instanceof HTMLElement ) {
      elem = option;
    }
    // use size of element, if element
    this[ measurement ] = elem ? getSize( elem )[ size ] : option;
  }
};

/**
 * layout a collection of item elements
 * @api public
 */
proto.layoutItems = function( items, isInstant ) {
  items = this._getItemsForLayout( items );

  this._layoutItems( items, isInstant );

  this._postLayout();
};

/**
 * get the items to be laid out
 * you may want to skip over some items
 * @param {Array} items
 * @returns {Array} items
 */
proto._getItemsForLayout = function( items ) {
  return items.filter( function( item ) {
    return !item.isIgnored;
  });
};

/**
 * layout items
 * @param {Array} items
 * @param {Boolean} isInstant
 */
proto._layoutItems = function( items, isInstant ) {
  this._emitCompleteOnItems( 'layout', items );

  if ( !items || !items.length ) {
    // no items, emit event with empty array
    return;
  }

  var queue = [];

  items.forEach( function( item ) {
    // get x/y object from method
    var position = this._getItemLayoutPosition( item );
    // enqueue
    position.item = item;
    position.isInstant = isInstant || item.isLayoutInstant;
    queue.push( position );
  }, this );

  this._processLayoutQueue( queue );
};

/**
 * get item layout position
 * @param {Outlayer.Item} item
 * @returns {Object} x and y position
 */
proto._getItemLayoutPosition = function( /* item */ ) {
  return {
    x: 0,
    y: 0
  };
};

/**
 * iterate over array and position each item
 * Reason being - separating this logic prevents 'layout invalidation'
 * thx @paul_irish
 * @param {Array} queue
 */
proto._processLayoutQueue = function( queue ) {
  this.updateStagger();
  queue.forEach( function( obj, i ) {
    this._positionItem( obj.item, obj.x, obj.y, obj.isInstant, i );
  }, this );
};

// set stagger from option in milliseconds number
proto.updateStagger = function() {
  var stagger = this.options.stagger;
  if ( stagger === null || stagger === undefined ) {
    this.stagger = 0;
    return;
  }
  this.stagger = getMilliseconds( stagger );
  return this.stagger;
};

/**
 * Sets position of item in DOM
 * @param {Outlayer.Item} item
 * @param {Number} x - horizontal position
 * @param {Number} y - vertical position
 * @param {Boolean} isInstant - disables transitions
 */
proto._positionItem = function( item, x, y, isInstant, i ) {
  if ( isInstant ) {
    // if not transition, just set CSS
    item.goTo( x, y );
  } else {
    item.stagger( i * this.stagger );
    item.moveTo( x, y );
  }
};

/**
 * Any logic you want to do after each layout,
 * i.e. size the container
 */
proto._postLayout = function() {
  this.resizeContainer();
};

proto.resizeContainer = function() {
  var isResizingContainer = this._getOption('resizeContainer');
  if ( !isResizingContainer ) {
    return;
  }
  var size = this._getContainerSize();
  if ( size ) {
    this._setContainerMeasure( size.width, true );
    this._setContainerMeasure( size.height, false );
  }
};

/**
 * Sets width or height of container if returned
 * @returns {Object} size
 *   @param {Number} width
 *   @param {Number} height
 */
proto._getContainerSize = noop;

/**
 * @param {Number} measure - size of width or height
 * @param {Boolean} isWidth
 */
proto._setContainerMeasure = function( measure, isWidth ) {
  if ( measure === undefined ) {
    return;
  }

  var elemSize = this.size;
  // add padding and border width if border box
  if ( elemSize.isBorderBox ) {
    measure += isWidth ? elemSize.paddingLeft + elemSize.paddingRight +
      elemSize.borderLeftWidth + elemSize.borderRightWidth :
      elemSize.paddingBottom + elemSize.paddingTop +
      elemSize.borderTopWidth + elemSize.borderBottomWidth;
  }

  measure = Math.max( measure, 0 );
  this.element.style[ isWidth ? 'width' : 'height' ] = measure + 'px';
};

/**
 * emit eventComplete on a collection of items events
 * @param {String} eventName
 * @param {Array} items - Outlayer.Items
 */
proto._emitCompleteOnItems = function( eventName, items ) {
  var _this = this;
  function onComplete() {
    _this.dispatchEvent( eventName + 'Complete', null, [ items ] );
  }

  var count = items.length;
  if ( !items || !count ) {
    onComplete();
    return;
  }

  var doneCount = 0;
  function tick() {
    doneCount++;
    if ( doneCount == count ) {
      onComplete();
    }
  }

  // bind callback
  items.forEach( function( item ) {
    item.once( eventName, tick );
  });
};

/**
 * emits events via EvEmitter and jQuery events
 * @param {String} type - name of event
 * @param {Event} event - original event
 * @param {Array} args - extra arguments
 */
proto.dispatchEvent = function( type, event, args ) {
  // add original event to arguments
  var emitArgs = event ? [ event ].concat( args ) : args;
  this.emitEvent( type, emitArgs );

  if ( jQuery ) {
    // set this.$element
    this.$element = this.$element || jQuery( this.element );
    if ( event ) {
      // create jQuery event
      var $event = jQuery.Event( event );
      $event.type = type;
      this.$element.trigger( $event, args );
    } else {
      // just trigger with type if no event available
      this.$element.trigger( type, args );
    }
  }
};

// -------------------------- ignore & stamps -------------------------- //


/**
 * keep item in collection, but do not lay it out
 * ignored items do not get skipped in layout
 * @param {Element} elem
 */
proto.ignore = function( elem ) {
  var item = this.getItem( elem );
  if ( item ) {
    item.isIgnored = true;
  }
};

/**
 * return item to layout collection
 * @param {Element} elem
 */
proto.unignore = function( elem ) {
  var item = this.getItem( elem );
  if ( item ) {
    delete item.isIgnored;
  }
};

/**
 * adds elements to stamps
 * @param {NodeList, Array, Element, or String} elems
 */
proto.stamp = function( elems ) {
  elems = this._find( elems );
  if ( !elems ) {
    return;
  }

  this.stamps = this.stamps.concat( elems );
  // ignore
  elems.forEach( this.ignore, this );
};

/**
 * removes elements to stamps
 * @param {NodeList, Array, or Element} elems
 */
proto.unstamp = function( elems ) {
  elems = this._find( elems );
  if ( !elems ){
    return;
  }

  elems.forEach( function( elem ) {
    // filter out removed stamp elements
    utils.removeFrom( this.stamps, elem );
    this.unignore( elem );
  }, this );
};

/**
 * finds child elements
 * @param {NodeList, Array, Element, or String} elems
 * @returns {Array} elems
 */
proto._find = function( elems ) {
  if ( !elems ) {
    return;
  }
  // if string, use argument as selector string
  if ( typeof elems == 'string' ) {
    elems = this.element.querySelectorAll( elems );
  }
  elems = utils.makeArray( elems );
  return elems;
};

proto._manageStamps = function() {
  if ( !this.stamps || !this.stamps.length ) {
    return;
  }

  this._getBoundingRect();

  this.stamps.forEach( this._manageStamp, this );
};

// update boundingLeft / Top
proto._getBoundingRect = function() {
  // get bounding rect for container element
  var boundingRect = this.element.getBoundingClientRect();
  var size = this.size;
  this._boundingRect = {
    left: boundingRect.left + size.paddingLeft + size.borderLeftWidth,
    top: boundingRect.top + size.paddingTop + size.borderTopWidth,
    right: boundingRect.right - ( size.paddingRight + size.borderRightWidth ),
    bottom: boundingRect.bottom - ( size.paddingBottom + size.borderBottomWidth )
  };
};

/**
 * @param {Element} stamp
**/
proto._manageStamp = noop;

/**
 * get x/y position of element relative to container element
 * @param {Element} elem
 * @returns {Object} offset - has left, top, right, bottom
 */
proto._getElementOffset = function( elem ) {
  var boundingRect = elem.getBoundingClientRect();
  var thisRect = this._boundingRect;
  var size = getSize( elem );
  var offset = {
    left: boundingRect.left - thisRect.left - size.marginLeft,
    top: boundingRect.top - thisRect.top - size.marginTop,
    right: thisRect.right - boundingRect.right - size.marginRight,
    bottom: thisRect.bottom - boundingRect.bottom - size.marginBottom
  };
  return offset;
};

// -------------------------- resize -------------------------- //

// enable event handlers for listeners
// i.e. resize -> onresize
proto.handleEvent = utils.handleEvent;

/**
 * Bind layout to window resizing
 */
proto.bindResize = function() {
  window.addEventListener( 'resize', this );
  this.isResizeBound = true;
};

/**
 * Unbind layout to window resizing
 */
proto.unbindResize = function() {
  window.removeEventListener( 'resize', this );
  this.isResizeBound = false;
};

proto.onresize = function() {
  this.resize();
};

utils.debounceMethod( Outlayer, 'onresize', 100 );

proto.resize = function() {
  // don't trigger if size did not change
  // or if resize was unbound. See #9
  if ( !this.isResizeBound || !this.needsResizeLayout() ) {
    return;
  }

  this.layout();
};

/**
 * check if layout is needed post layout
 * @returns Boolean
 */
proto.needsResizeLayout = function() {
  var size = getSize( this.element );
  // check that this.size and size are there
  // IE8 triggers resize on body size change, so they might not be
  var hasSizes = this.size && size;
  return hasSizes && size.innerWidth !== this.size.innerWidth;
};

// -------------------------- methods -------------------------- //

/**
 * add items to Outlayer instance
 * @param {Array or NodeList or Element} elems
 * @returns {Array} items - Outlayer.Items
**/
proto.addItems = function( elems ) {
  var items = this._itemize( elems );
  // add items to collection
  if ( items.length ) {
    this.items = this.items.concat( items );
  }
  return items;
};

/**
 * Layout newly-appended item elements
 * @param {Array or NodeList or Element} elems
 */
proto.appended = function( elems ) {
  var items = this.addItems( elems );
  if ( !items.length ) {
    return;
  }
  // layout and reveal just the new items
  this.layoutItems( items, true );
  this.reveal( items );
};

/**
 * Layout prepended elements
 * @param {Array or NodeList or Element} elems
 */
proto.prepended = function( elems ) {
  var items = this._itemize( elems );
  if ( !items.length ) {
    return;
  }
  // add items to beginning of collection
  var previousItems = this.items.slice(0);
  this.items = items.concat( previousItems );
  // start new layout
  this._resetLayout();
  this._manageStamps();
  // layout new stuff without transition
  this.layoutItems( items, true );
  this.reveal( items );
  // layout previous items
  this.layoutItems( previousItems );
};

/**
 * reveal a collection of items
 * @param {Array of Outlayer.Items} items
 */
proto.reveal = function( items ) {
  this._emitCompleteOnItems( 'reveal', items );
  if ( !items || !items.length ) {
    return;
  }
  var stagger = this.updateStagger();
  items.forEach( function( item, i ) {
    item.stagger( i * stagger );
    item.reveal();
  });
};

/**
 * hide a collection of items
 * @param {Array of Outlayer.Items} items
 */
proto.hide = function( items ) {
  this._emitCompleteOnItems( 'hide', items );
  if ( !items || !items.length ) {
    return;
  }
  var stagger = this.updateStagger();
  items.forEach( function( item, i ) {
    item.stagger( i * stagger );
    item.hide();
  });
};

/**
 * reveal item elements
 * @param {Array}, {Element}, {NodeList} items
 */
proto.revealItemElements = function( elems ) {
  var items = this.getItems( elems );
  this.reveal( items );
};

/**
 * hide item elements
 * @param {Array}, {Element}, {NodeList} items
 */
proto.hideItemElements = function( elems ) {
  var items = this.getItems( elems );
  this.hide( items );
};

/**
 * get Outlayer.Item, given an Element
 * @param {Element} elem
 * @param {Function} callback
 * @returns {Outlayer.Item} item
 */
proto.getItem = function( elem ) {
  // loop through items to get the one that matches
  for ( var i=0; i < this.items.length; i++ ) {
    var item = this.items[i];
    if ( item.element == elem ) {
      // return item
      return item;
    }
  }
};

/**
 * get collection of Outlayer.Items, given Elements
 * @param {Array} elems
 * @returns {Array} items - Outlayer.Items
 */
proto.getItems = function( elems ) {
  elems = utils.makeArray( elems );
  var items = [];
  elems.forEach( function( elem ) {
    var item = this.getItem( elem );
    if ( item ) {
      items.push( item );
    }
  }, this );

  return items;
};

/**
 * remove element(s) from instance and DOM
 * @param {Array or NodeList or Element} elems
 */
proto.remove = function( elems ) {
  var removeItems = this.getItems( elems );

  this._emitCompleteOnItems( 'remove', removeItems );

  // bail if no items to remove
  if ( !removeItems || !removeItems.length ) {
    return;
  }

  removeItems.forEach( function( item ) {
    item.remove();
    // remove item from collection
    utils.removeFrom( this.items, item );
  }, this );
};

// ----- destroy ----- //

// remove and disable Outlayer instance
proto.destroy = function() {
  // clean up dynamic styles
  var style = this.element.style;
  style.height = '';
  style.position = '';
  style.width = '';
  // destroy items
  this.items.forEach( function( item ) {
    item.destroy();
  });

  this.unbindResize();

  var id = this.element.outlayerGUID;
  delete instances[ id ]; // remove reference to instance by id
  delete this.element.outlayerGUID;
  // remove data for jQuery
  if ( jQuery ) {
    jQuery.removeData( this.element, this.constructor.namespace );
  }

};

// -------------------------- data -------------------------- //

/**
 * get Outlayer instance from element
 * @param {Element} elem
 * @returns {Outlayer}
 */
Outlayer.data = function( elem ) {
  elem = utils.getQueryElement( elem );
  var id = elem && elem.outlayerGUID;
  return id && instances[ id ];
};


// -------------------------- create Outlayer class -------------------------- //

/**
 * create a layout class
 * @param {String} namespace
 */
Outlayer.create = function( namespace, options ) {
  // sub-class Outlayer
  var Layout = subclass( Outlayer );
  // apply new options and compatOptions
  Layout.defaults = utils.extend( {}, Outlayer.defaults );
  utils.extend( Layout.defaults, options );
  Layout.compatOptions = utils.extend( {}, Outlayer.compatOptions  );

  Layout.namespace = namespace;

  Layout.data = Outlayer.data;

  // sub-class Item
  Layout.Item = subclass( Item );

  // -------------------------- declarative -------------------------- //

  utils.htmlInit( Layout, namespace );

  // -------------------------- jQuery bridge -------------------------- //

  // make into jQuery plugin
  if ( jQuery && jQuery.bridget ) {
    jQuery.bridget( namespace, Layout );
  }

  return Layout;
};

function subclass( Parent ) {
  function SubClass() {
    Parent.apply( this, arguments );
  }

  SubClass.prototype = Object.create( Parent.prototype );
  SubClass.prototype.constructor = SubClass;

  return SubClass;
}

// ----- helpers ----- //

// how many milliseconds are in each unit
var msUnits = {
  ms: 1,
  s: 1000
};

// munge time-like parameter into millisecond number
// '0.4s' -> 40
function getMilliseconds( time ) {
  if ( typeof time == 'number' ) {
    return time;
  }
  var matches = time.match( /(^\d*\.?\d*)(\w*)/ );
  var num = matches && matches[1];
  var unit = matches && matches[2];
  if ( !num.length ) {
    return 0;
  }
  num = parseFloat( num );
  var mult = msUnits[ unit ] || 1;
  return num * mult;
}

// ----- fin ----- //

// back in global
Outlayer.Item = Item;

return Outlayer;

}));

/*!
 * Masonry v4.2.2
 * Cascading grid layout library
 * https://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( [
        'outlayer/outlayer',
        'get-size/get-size'
      ],
      factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      require('outlayer'),
      require('get-size')
    );
  } else {
    // browser global
    window.Masonry = factory(
      window.Outlayer,
      window.getSize
    );
  }

}( window, function factory( Outlayer, getSize ) {



// -------------------------- masonryDefinition -------------------------- //

  // create an Outlayer layout class
  var Masonry = Outlayer.create('masonry');
  // isFitWidth -> fitWidth
  Masonry.compatOptions.fitWidth = 'isFitWidth';

  var proto = Masonry.prototype;

  proto._resetLayout = function() {
    this.getSize();
    this._getMeasurement( 'columnWidth', 'outerWidth' );
    this._getMeasurement( 'gutter', 'outerWidth' );
    this.measureColumns();

    // reset column Y
    this.colYs = [];
    for ( var i=0; i < this.cols; i++ ) {
      this.colYs.push( 0 );
    }

    this.maxY = 0;
    this.horizontalColIndex = 0;
  };

  proto.measureColumns = function() {
    this.getContainerWidth();
    // if columnWidth is 0, default to outerWidth of first item
    if ( !this.columnWidth ) {
      var firstItem = this.items[0];
      var firstItemElem = firstItem && firstItem.element;
      // columnWidth fall back to item of first element
      this.columnWidth = firstItemElem && getSize( firstItemElem ).outerWidth ||
        // if first elem has no width, default to size of container
        this.containerWidth;
    }

    var columnWidth = this.columnWidth += this.gutter;

    // calculate columns
    var containerWidth = this.containerWidth + this.gutter;
    var cols = containerWidth / columnWidth;
    // fix rounding errors, typically with gutters
    var excess = columnWidth - containerWidth % columnWidth;
    // if overshoot is less than a pixel, round up, otherwise floor it
    var mathMethod = excess && excess < 1 ? 'round' : 'floor';
    cols = Math[ mathMethod ]( cols );
    this.cols = Math.max( cols, 1 );
  };

  proto.getContainerWidth = function() {
    // container is parent if fit width
    var isFitWidth = this._getOption('fitWidth');
    var container = isFitWidth ? this.element.parentNode : this.element;
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var size = getSize( container );
    this.containerWidth = size && size.innerWidth;
  };

  proto._getItemLayoutPosition = function( item ) {
    item.getSize();
    // how many columns does this brick span
    var remainder = item.size.outerWidth % this.columnWidth;
    var mathMethod = remainder && remainder < 1 ? 'round' : 'ceil';
    // round if off by 1 pixel, otherwise use ceil
    var colSpan = Math[ mathMethod ]( item.size.outerWidth / this.columnWidth );
    colSpan = Math.min( colSpan, this.cols );
    // use horizontal or top column position
    var colPosMethod = this.options.horizontalOrder ?
      '_getHorizontalColPosition' : '_getTopColPosition';
    var colPosition = this[ colPosMethod ]( colSpan, item );
    // position the brick
    var position = {
      x: this.columnWidth * colPosition.col,
      y: colPosition.y
    };
    // apply setHeight to necessary columns
    var setHeight = colPosition.y + item.size.outerHeight;
    var setMax = colSpan + colPosition.col;
    for ( var i = colPosition.col; i < setMax; i++ ) {
      this.colYs[i] = setHeight;
    }

    return position;
  };

  proto._getTopColPosition = function( colSpan ) {
    var colGroup = this._getTopColGroup( colSpan );
    // get the minimum Y value from the columns
    var minimumY = Math.min.apply( Math, colGroup );

    return {
      col: colGroup.indexOf( minimumY ),
      y: minimumY,
    };
  };

  /**
   * @param {Number} colSpan - number of columns the element spans
   * @returns {Array} colGroup
   */
  proto._getTopColGroup = function( colSpan ) {
    if ( colSpan < 2 ) {
      // if brick spans only one column, use all the column Ys
      return this.colYs;
    }

    var colGroup = [];
    // how many different places could this brick fit horizontally
    var groupCount = this.cols + 1 - colSpan;
    // for each group potential horizontal position
    for ( var i = 0; i < groupCount; i++ ) {
      colGroup[i] = this._getColGroupY( i, colSpan );
    }
    return colGroup;
  };

  proto._getColGroupY = function( col, colSpan ) {
    if ( colSpan < 2 ) {
      return this.colYs[ col ];
    }
    // make an array of colY values for that one group
    var groupColYs = this.colYs.slice( col, col + colSpan );
    // and get the max value of the array
    return Math.max.apply( Math, groupColYs );
  };

  // get column position based on horizontal index. #873
  proto._getHorizontalColPosition = function( colSpan, item ) {
    var col = this.horizontalColIndex % this.cols;
    var isOver = colSpan > 1 && col + colSpan > this.cols;
    // shift to next row if item can't fit on current row
    col = isOver ? 0 : col;
    // don't let zero-size items take up space
    var hasSize = item.size.outerWidth && item.size.outerHeight;
    this.horizontalColIndex = hasSize ? col + colSpan : this.horizontalColIndex;

    return {
      col: col,
      y: this._getColGroupY( col, colSpan ),
    };
  };

  proto._manageStamp = function( stamp ) {
    var stampSize = getSize( stamp );
    var offset = this._getElementOffset( stamp );
    // get the columns that this stamp affects
    var isOriginLeft = this._getOption('originLeft');
    var firstX = isOriginLeft ? offset.left : offset.right;
    var lastX = firstX + stampSize.outerWidth;
    var firstCol = Math.floor( firstX / this.columnWidth );
    firstCol = Math.max( 0, firstCol );
    var lastCol = Math.floor( lastX / this.columnWidth );
    // lastCol should not go over if multiple of columnWidth #425
    lastCol -= lastX % this.columnWidth ? 0 : 1;
    lastCol = Math.min( this.cols - 1, lastCol );
    // set colYs to bottom of the stamp

    var isOriginTop = this._getOption('originTop');
    var stampMaxY = ( isOriginTop ? offset.top : offset.bottom ) +
      stampSize.outerHeight;
    for ( var i = firstCol; i <= lastCol; i++ ) {
      this.colYs[i] = Math.max( stampMaxY, this.colYs[i] );
    }
  };

  proto._getContainerSize = function() {
    this.maxY = Math.max.apply( Math, this.colYs );
    var size = {
      height: this.maxY
    };

    if ( this._getOption('fitWidth') ) {
      size.width = this._getContainerFitWidth();
    }

    return size;
  };

  proto._getContainerFitWidth = function() {
    var unusedCols = 0;
    // count unused columns
    var i = this.cols;
    while ( --i ) {
      if ( this.colYs[i] !== 0 ) {
        break;
      }
      unusedCols++;
    }
    // fit container to columns that have been used
    return ( this.cols - unusedCols ) * this.columnWidth - this.gutter;
  };

  proto.needsResizeLayout = function() {
    var previousWidth = this.containerWidth;
    this.getContainerWidth();
    return previousWidth != this.containerWidth;
  };

  return Masonry;

}));

