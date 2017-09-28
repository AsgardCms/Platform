$(function () {
  'use strict'

  $.get('/adsync', function (response) {
    var col = $('<div />').html(response)

    $('.content-wrapper .content').append(col)
  })

  $(document).on('click', '.ad-click-event', function (e) {
    e.preventDefault()
    var category = 'Premium Template'
    var action   = ''
    if ($(e.target).is('img')) {
      action = 'Image Buy Now'
    } else {
      action = $(this).text().toLowerCase().indexOf('buy') > -1 ? 'Buy Now' : 'Preview'
    }

    var label = $(this).attr('href')
    var went  = false

    function go() {
      if (!went) {
        went                 = true
        window.location.href = label
      }
    }

    setTimeout(go, 500)

    ga('send', 'event', {
      eventCategory: category,
      eventAction  : action,
      eventLabel   : label,
      transport    : 'beacon',
      hitCallback  : go,
      dimension1   : window.location.pathname + window.location.search + window.location.hash,
      dimension2   : window.location.host
    })
  })
})

$(function () {
  'use strict'
  var i = $('<i />', { 'class': 'fa fa-star-o' })
  i.css('color', '#fff')
  var a = $('<a />', { href: 'https://themequarry.com' })
  a.append(i)
  var span = $('<span />')
  span.append('Premium Templates')
  span.css('color', '#fff')
  a.append(span)
  var li = $('<li />', { 'class': 'bg-green' })
  li.append(a)
  li.on('mouseover', function () {
    $(this).find('a').first().css('background-color', '#008d4c')
    $(this).find('.fa').removeClass('fa-star-o').addClass('fa-star')
  })
  li.on('mouseout', function () {
    $(this).find('a').first().css('background-color', '#00a65a')
    $(this).find('.fa').removeClass('fa-star').addClass('fa-star-o')
  })
  $('.sidebar-menu').append(li)
})

$(function () {
  'use strict'

  var ds = window.localStorage
  if (ds && ds.getItem('no_show') != null) {
    return
  }

  /**
   * Create ThemeQuarry ad
   */
  var wrapper_css = {
    'padding'    : '20px 30px',
    'background' : '#f39c12',
    'display'    : 'none',
    'z-index'    : '999999',
    'font-size'  : '16px',
    'font-weight': 600
  }

  var link_css = {
    'color'          : 'rgba(255, 255, 255, 0.9)',
    'display'        : 'inline-block',
    'margin-right'   : '10px',
    'text-decoration': 'none'
  }

  var link_hover_css = {
    'text-decoration': 'underline',
    'color'          : '#f9f9f9'
  }

  var btn_css = {
    'margin-top' : '-5px',
    'border'     : '0',
    'box-shadow' : 'none',
    'color'      : '#f39c12',
    'font-weight': '600',
    'background' : '#fff'
  }

  var close_css = {
    'color'    : '#fff',
    'font-size': '20px'
  }

  var wrapper = $('<div />').css(wrapper_css)
  var link    = $('<a />', { href: 'https://themequarry.com' })
    .html('Ready to sell your theme? Submit your theme to our new marketplace now and let over 200k visitors see it!')
    .css(link_css)
    .hover(function () {
      $(this).css(link_hover_css)
    }, function () {
      $(this).css(link_css)
    })
  var btn     = $('<a />', {
    'class': 'btn btn-default btn-sm',
    href   : 'https://themequarry.com'
  }).html('Let\'s Do It!').css(btn_css)
  var close   = $('<a />', {
    'class'         : 'pull-right',
    href            : '#',
    'data-toggle'   : 'tooltip',
    'data-placement': 'left',
    'title'         : 'Never show me this again!'
  }).html('&times;')
    .css(close_css)
    .click(function (e) {
      e.preventDefault()
      $(wrapper).slideUp()
      if (ds) {
        ds.setItem('no_show', true)
      }
    })

  wrapper.append(close)
  wrapper.append(link)
  wrapper.append(btn)

  $('.content-wrapper').prepend(wrapper)

  wrapper.hide(4).delay(500).slideDown()
});
(function (i, s, o, g, r, a, m) {
  i['GoogleAnalyticsObject'] = r
  i[r] = i[r] || function () {
    (i[r].q = i[r].q || []).push(arguments)
  }, i[r].l = 1 * new Date()
  a = s.createElement(o),
    m = s.getElementsByTagName(o)[0]
  a.async = 1
  a.src   = g
  m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga')

ga('create', 'UA-46680343-1', 'auto')
ga('send', 'pageview')