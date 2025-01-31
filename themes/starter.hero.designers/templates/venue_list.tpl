<div class="arlo" id="arlo">

[arlo_venue_list_item]

    <div class="venue m-b-20 bg-white drop-shadow padding-20 md-padding-15">
      <h1 class="m-t-0 m-b-0">[arlo_venue_permalink wrap="<a href='%s'>"][arlo_venue_name]</a></h1>
        <div class="arlo-map">
            [arlo_venue_map height="175" width="1000"]
        </div>
      <div class="m-t-10">
        <i class="icons8-marker pull-left lh-25"></i>
        <p class="m-l-20">
            [arlo_venue_address layout="string" items="line1" wrap="%s"]
            [arlo_venue_address layout="string" items="line2" wrap="<br>%s"]
            [arlo_venue_address layout="string" items="line3" wrap="<br>%s"]
            [arlo_venue_address layout="string" items="line4" wrap="<br>%s"]
            [arlo_venue_address layout="string" items="suburb,city" wrap="<br>%s"]
            [arlo_venue_address layout="string" items="state" wrap="%s"]
            [arlo_venue_address layout="string" items="post_code" wrap="%s"]
            [arlo_venue_address layout="string" items="country" wrap="<br>%s"]
        </p>
      </div>
      
      <div class="row row-fix directions-parking">
        <div class="col-xs-12 col-md-6 md-p-l-0 md-p-r-0">
          [arlo_venue_directions label='<h4>Directions</h4>']
        </div>
        <div class="col-xs-12 col-md-6 md-p-l-0 md-p-r-0">
          [arlo_venue_parking label='<h4>Parking</h4>']
        </div>
      </div>
      <a class="btn btn-secondary m-t-10 m-r-10" href="schedule" target="_blank">View schedule</a> <a class="btn btn-secondary m-t-10 m-r-10" href="upcoming" target="_blank">View upcoming</a>
    </div>
        
    [arlo_venue_rich_snippet]
[/arlo_venue_list_item]

[arlo_venue_list_pagination]

[arlo_powered_by]

</div>