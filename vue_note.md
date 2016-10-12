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
v-on:click

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