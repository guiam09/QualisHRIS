(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/includes/validation', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.formsValidation = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });


  // Example Validataion Full
  // ------------------------
  (function () {
    (0, _jquery2.default)('#loginForm').formValidation({
      framework: "bootstrap4",
      button: {
        selector: '#submit',
        disabled: 'disabled'
      },
      icon: null,
      fields: {
        lastName: {
          validators: {
            notEmpty: {
              message: 'Last Name is required'
            },
            stringLength: {
              min: 1,
              max: 30
            },
            regexp: {
              regexp: /^[a-zA-Z0-9]+$/
            }
          }
        },
        firstName: {
          validators: {
            notEmpty: {
              message: 'First Name is required'
            },
            stringLength: {
              min: 1,
              max: 30
            },
            regexp: {
              regexp: /^[a-zA-Z0-9]+$/
            }
          }
        },
        middleName: {
          validators: {
            stringLength: {
              min: 0,
              max: 30
            },
            regexp: {
              regexp: /^[a-zA-Z0-9]+$/
            }
          }
        },
        contactNumber: {
          validators: {
            integer: {
              message: 'The value is not a number'
            }
          }
        },
        birthdate: {
          validators: {
            notEmpty: {
              message: 'The birthday is required'
            },
          }
        },
        username: {
          validators: {
            notEmpty: {
              message: 'The username is required'
            },
          }
        },
        password: {
          validators: {
            notEmpty: {
              message: 'The password is required'
            },
          }
        },
        civilStatus: {
          validators: {
            notEmpty: {
              message: 'Civil Status is required'
            },
          }
        },
        emailAddress: {
          validators: {
            notEmpty: {
              message: 'The email address is required'
            },
            emailAddress: {
              message: 'The email address is not valid'
            }
          }
        },
        address: {
          validators: {
            notEmpty: {
              message: 'The address is required'
            },
          }
        },
        password: {
          validators: {
            notEmpty: {
              message: 'The password is required'
            },
            stringLength: {
              min: 8
            }
          }
        },
        birthday: {
          validators: {
            notEmpty: {
              message: 'The birthday is required'
            },
            date: {
              format: 'YYYY/MM/DD'
            }
          }
        },
        skills: {
          validators: {
            notEmpty: {
              message: 'The skills is required'
            },
            stringLength: {
              max: 300
            }
          }
        },
        gender: {
          validators: {
            notEmpty: {
              message: 'Please select a gender'
            }
          }
        },
        dependentName: {
          validators: {
            stringLength: {
              min: 1,
              max: 30
            },
            regexp: {
              regexp: /^[a-zA-Z0-9]+$/
            }
          }
        },
        dependentRelationship: {
          validators: {
          }
        },
        accessLevel: {
          validators: {
            notEmpty: {
              message: 'Please specify one'
            }
          }
        },
        coreTime: {
          validators: {
            notEmpty: {
              message: 'Please specify one'
            }
          }
        },
        department: {
          validators: {
            notEmpty: {
              message: 'Please specify one'
            }
          }
        },
        position: {
          validators: {
            notEmpty: {
              message: 'Please specify one'
            }
          }
        },
        dateHired: {
          validators: {
            notEmpty: {
              message: 'Please specify'
            }
          }
        },
        reportingTo: {
          validators: {
            notEmpty: {
              message: 'Please specify one'
            }
          }
        },

        'for[]': {
          validators: {
            notEmpty: {
              message: 'Please specify at least one'
            }
          }
        },
        company: {
          validators: {
            notEmpty: {
              message: 'Please company'
            }
          }
        },
        browsers: {
          validators: {
            notEmpty: {
              message: 'Please specify at least one browser you use daily for development'
            }
          }
        }
      },
      err: {
        clazz: 'invalid-feedback'
      },
      control: {
        // The CSS class for valid control
        valid: 'is-valid',

        // The CSS class for invalid control
        invalid: 'is-invalid'
      },
      row: {
        invalid: 'has-danger'
      }
    });
  })();

});
