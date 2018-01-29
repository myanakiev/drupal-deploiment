hello.hello:
  path: ‘/hello/{param}’
  defaults:
    _title: ‘Hello’
    _controller: ‘\Drupal\hello\Controller\HelloController::content’
    param: ‘no parameter’
  requirements:
    _access: ‘TRUE’

hello.node_history:
  path: ‘/node/{node}/history’
  defaults:
    _title: ‘Node update history’
    _controller: ‘\Drupal\hello\Controller\HelloNodeHistoryController::content’
  requirements:
   _access: ‘TRUE’

hello.calculator:
  path: ‘/calculator’
  defaults:
    _title: ‘Calculator’
    _form: ‘\Drupal\hello\Form\HelloCalculator’
  requirements:
   _access: ‘TRUE’

hello.calculator.result:
  path: ‘/calculator-result/{result}’
  defaults:
    _title: ‘Result’
    _controller: ‘\Drupal\hello\Controller\HelloCalculatorResult::content’
  requirements:
   _access: ‘TRUE’
