$(->
  $(".collapse").each ->
    $(@).find(".collapse-link").on "click", ->
      parent = $(@).parent()
      if parent.hasClass("active")
        parent.removeClass("active")
      else
        parent.addClass("active")
      false
)
