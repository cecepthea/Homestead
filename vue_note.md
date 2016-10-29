##link data and the DOM

```

{{ message }} # in text node, can only contain one single expression

{{ message | filter | filterb('arg1', expression) }}

============

v-bind # in element attributes.

the attribute will be removed if the condition evaluates to a falsy value:

<button v-bind:disabled="someDynamicCondition">Button</button>

============

v-model on input, two way with data

But sometimes we may want to bind the value to a dynamic property on the Vue instance. We can use v-bind to achieve that. In addition, using v-bind allows us to bind the input value to non-string values.

<input type="checkbox" v-model="toggle" v-bind:true-value="a" v-bind:false-value="b">
  
// when checked:
vm.toggle === vm.a
// when unchecked:
vm.toggle === vm.b

在自定义组件上使用v-model

# html

<div id="v-model-example">
  <p>{{ message }}</p>
  <my-input
    label="Message"
    v-model="message" <!-- pass to props value, MUST -->
  ></my-input>
</div>

# javascript

Vue.component('my-input', {
  template: '\
    <div class="form-group">\
      <label v-bind:for="randomId">{{ label }}:</label>\
      <input v-bind:id="randomId" v-bind:value="value" <!-- MUST --> v-on:input="onInput">\
    </div>\
  ',
  props: ['value', 'label'],
  data: function () {
    return {
      randomId: 'input-' + Math.random()
    }
  },
  methods: {
    onInput: function (event) {
      this.$emit('input', event.target.value);
      // emit an input event with the new value, MUST
    }
  },
})
new Vue({
  el: '#v-model-example',
  data: {
    message: 'hello'
  }
})

============

v-once interpolations only once

v-html interprets as realHTML not plaintext,data bindings are ignored

```

Computed Properties

```
html:
<div id="example">
  <p>Original message: "{{ message }}"</p>
  <p>Computed reversed message: "{{ reversedMessage }}"</p>
</div>

javascript:
var vm = new Vue({
  el: '#example',
  data: {
    message: 'Hello'
  },
  computed: { // methods also have same effect,but computed are cached
    // a computed getter
    reversedMessage: function () { // default is getter,also can define setter
      // `this` points to the vm instance
      return this.message.split('').reverse().join('')
    }
  }
})

console.log(vm.reversedMessage) // -> 'olleH'
vm.message = 'Goodbye'
console.log(vm.reversedMessage) // -> 'eybdooG'

```

## Class and Style Bindings

```

<div class="someStaticClass" v-bind:class="{ active: isActiveVariable }"></div>

<div v-bind:class="classObject"></div>

or use computed property as class data

<div v-bind:class="classObject"></div>

data: {
  isActive: true,
  error: null
},
computed: {
  classObject: function () {
    return {
      active: this.isActive && !this.error,
      'text-danger': this.error && this.error.type === 'fatal',
    }
  }
}

```


##Filters

```

new Vue({
  // ...
  filters: {
    filtera: function (value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
    }
  }
})

```

##Conditionals and Loops

```

v-if

<h1 v-if="ok">Yes</h1>
<h1 v-else>No</h1>

// toggle multi element

<template v-if="ok">
  <h1>Title</h1>
  <p>Paragraph 1</p>
  <p>Paragraph 2</p>
</template>

============

v-show , Another option for v-if

<h1 v-show="ok">Hello!</h1>

v-show will always be rendered and remain in the DOM;
v-show simply toggles the display CSS property of the element.

============

v-for

<div>
  <span v-for="n in 10">{{ n }}</span>
</div>

<ul id="example-1">
  <li v-for="item in items">
    {{ item.message }}
  </li>
</ul>

<ul>
  <template v-for="item in items">
    <li>{{ item.msg }}</li>
    <li class="divider"></li>
  </template>
</ul>

<div v-for="(value, key, index) in object">  // object
  {{ index }}. {{ key }} : {{ value }}
</div>

<my-component v-for="item in items" v-bind:item="item" v-bind:index="index"></my-component> 
  
// won’t automatically pass any data to the component,manual to pass the iterated data into the component.

Scope: Inside v-for blocks we have full access to parent scope properties

html:
<ul id="example-2">
  <li v-for="(item, index) in items">
    {{ parentMessage }} - {{ index }} - {{ item.message }}
  </li>
</ul>

javascript:
var example2 = new Vue({
  el: '#example-2',
  data: {
    parentMessage: 'Parent',
    items: [
      { message: 'Foo' },
      { message: 'Bar' }
    ]
  }
})

```

##Handling Event

```

# html
v-on:click="reverseMessage"

# javascript
new Vue({
  el: '#id',
  data: {
    message: 'Hello Vue.js!'
  },
  methods: {
    reverseMessage: function () {
      this.message = this.message.split('').reverse().join('')
    }
  }
})

============

绑定自定义事件

# html

<div id="counter-event-example">
  <p>{{ total }}</p>
  <button-counter v-on:increment="incrementTotal"></button-counter>
  <button-counter v-on:increment="incrementTotal"></button-counter>
  <!-- <button-counter v-on:click.native="doTheThing"></button-counter> -->
  <!-- 原生click事件，非手动 emit
</div>

# javascript

Vue.component('button-counter', {
  template: '<button v-on:click="increment">{{ counter }}</button>',
  data: function () {
    return {
      counter: 0
    }
  },
  methods: {
    increment: function () {
      this.counter += 1
      this.$emit('increment')
    }
  },
})
new Vue({
  el: '#counter-event-example',
  data: {
    total: 0
  },
  methods: {
    incrementTotal: function () {
      this.total += 1
    }
  }
})

```

## Directives

```

<p v-if="variable">Now you see me</p>

============

<a v-bind:href="url"></a>  

href is directive arguments, like <a v-on:click="doSomething">

============

<form v-on:submit.prevent="onSubmit"></form>

.prevent is modifier present event.preventDefault()

```

## Directives Shorthands

```

<!-- full syntax -->

<a v-bind:href="url"></a>

<!-- shorthand -->

<a :href="url"></a>

============

<!-- full syntax -->

<a v-on:click="doSomething"></a>

<!-- shorthand -->

<a @click="doSomething"></a>


```

##Components

```

// Define a new component called todo-item
Vue.component('todo-item', {
  // The todo-item component now accepts a
  // "prop", which is like a custom attribute.
  // This prop is called todo.
  props: ['todo'],
  template: '<li>{{ todo.text }}</li>'
})

<div id="app-7">
  <ol>
    <!--
    Now we provide each todo-item with the todo object
    it's representing, so that its content can be dynamic
    -->
    <todo-item v-for="todo in todos" v-bind:todo="todo"></todo-item>
  </ol>
</div>

var app7 = new Vue({
  el: '#app-7',
  data: {
    todos: [
      { text: 'Learn JavaScript' },
      { text: 'Learn Vue' },
      { text: 'Build something awesome' }
    ]
  }
})

----------------------------------------------------

Local Registration:

var Child = {
  template: '<div>A custom component!</div>'
}
new Vue({
  // ...
  components: {
    // <my-component> will only be available in parent's template
    'my-component': Child
  }
})

----------------------------------------------------

DOM Template Parsing Caveats

<table>
  <my-row>...</my-row>
</table>

The custom component <my-row> will be hoisted out as invalid content, thus causing errors in the eventual rendered output.(because browser restrictions) A workaround is to use the is special attribute:

<table>
  <tr is="my-row"></tr>
</table>

```

Slot

```

component:
<div>
  <h2>I'm the child title</h2>
  <slot>
    This will only be displayed if there is no content
    to be distributed.
  </slot>
</div>

parent:
<div>
  <h1>I'm the parent title</h1>
  <my-component>
    <!-- this content is to be distributed , will replace slot and it's content -->
    <p>This is some original content</p>
    <p>This is some more original content</p>
  </my-component>
</div>

result:
<div>
  <h1>I'm the parent title</h1>
  <div>
    <h2>I'm the child title</h2>
    <p>This is some original content</p>
    <p>This is some more original content</p>
  </div>
</div>

============

child:

<div class="container">
  <header>
    <slot name="header"></slot>
  </header>
  <main>
    <slot></slot>
  </main>
  <footer>
    <slot name="footer"></slot>
  </footer>
</div>

parent:

<app-layout>
  <h1 slot="header">Here might be a page title</h1>
  <p>A paragraph for the main content.</p>
  <p>And another one.</p>
  <p slot="footer">Here's some contact info</p>
</app-layout>

result:

<div class="container">
  <header>
    <h1>Here might be a page title</h1>
  </header>
  <main>
    <p>A paragraph for the main content.</p>
    <p>And another one.</p>
  </main>
  <footer>
    <p>Here's some contact info</p>
  </footer>
</div>

```

动态切换组件

```

var vm = new Vue({
  el: '#example',
  data: {
    currentView: 'home'
  },
  components: {
    home: { template: '<p>Welcome home!</p>' },
    posts: { /* ... */ },
    archive: { /* ... */ }
  }
})

<keep-alive><!-- 如果加keep-alive,则切换时加入内存中保留 -->
<component v-bind:is="currentView">
  <!-- component changes when vm.currentView changes! -->
  <!-- 组件在 vm.currentview 变化时改变 -->
</component>
<keep-alive>

保留的 <component> 元素

```

直接引用子组件

```
<div id="parent">
  <user-profile ref="profile"></user-profile>
</div>

var parent = new Vue({ el: '#parent' })
// access child component instance
// 访问子组件
var child = parent.$refs.profile

```

异步组件

```
// 推荐配合 webpack 使用

Vue.component('async-example', function (resolve, reject) {
  setTimeout(function () {
    resolve({
      template: '<div>I am async!</div>'
    })
  }, 1000)
})

Vue.component('async-webpack-example', function (resolve) {
  // This special require syntax will instruct Webpack to
  // automatically split your built code into bundles which
  // are loaded over Ajax requests.
  // 这个特殊的 require 语法告诉 webpack 自动将编译后的代码分割成不同的块，
  // 这些块将通过 ajax 请求自动下载。
  require(['./my-async-component'], resolve)
})

```

##Internal Properties and Methods

```

var data = { a: 1 }
var vm = new Vue({
  el: '#example',
  data: data
})
vm.$data === data // -> true
vm.$el === document.getElementById('example') // -> true
// $watch is an internal instance method
vm.$watch('a', function (newVal, oldVal) {
  // this callback will be called when `vm.a` changes
})

```