(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.gas_stations = {
    attach: function(context, settings) {

      const [container] = once(
        'edit-municipios',
        '#edit-municipios',
        context,
      );

      $("[id^=edit-municipios]").change(function () {
        console.log($(this).val())
        $.ajax({
          url: "/gas-stations/by-municipio/" + $(this).val(),
          method: 'GET',
          dataType: "json",
          success: function (result) {
            $('#gas-stations-table table tbody').empty()
            result.forEach(function(gasStation, index) {
              var line = $('<tr>');
              line.append($('<td>').text(gasStation['Localidad']));
              line.append($('<td>').text(gasStation['Dirección']));
              line.append($('<td>').text(gasStation['Rótulo']));
              line.append($('<td>').text(gasStation['Precio Gasoleo A']));
              line.append($('<td>').text(gasStation['Precio Gasoleo B']));
              line.append($('<td>').text(gasStation['Precio Gasolina 95 E5']));
              line.append($('<td>').text(gasStation['Precio Gasolina 98 E10']));
              line.appendTo($('#gas-stations-table table tbody'))
            })
            $('#gas-stations-table table').removeClass('d-none')
          },
          error: function(error) {
            console.log(error)
          }
        })
      })

    }
  }
})(jQuery, Drupal);