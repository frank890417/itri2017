<template lang="pug">
  div(id="divGauge")
    div.graph_gauge(id="power-gauge")
    span 年耗電量:
    span {{dataValue}}

</template>


<script>
import { mapState, mapMutations } from "vuex";

export default {
  name: "page_solution",
  //props: ["datas", "size", "use_unit", "use_power", "use_mul"],
  data() {
    return {
      //dataValue:1100,
    };
  },
  components: {},
  watch: {},
  computed: {
    ...mapState(["device_result"]),
     dataValue(){
       return store.state.KWH;
     },

  },
  methods: {
    ...mapMutations([])
  },
  mounted() {
    var gauge = function(container, configuration) {
      var that = {};
      var config = {
        size						: 710,
        clipWidth					: 200,
        clipHeight					: 110,
        ringInset					: 20,
        ringWidth					: 20,

        pointerWidth				: 10,
        pointerTailLength			: 5,
        pointerHeadLengthPercent	: 0.9,

        minValue					: 0,
        maxValue					: 10,

        minAngle					: -90,
        maxAngle					: 90,

        transitionMs				: 750,

        majorTicks					: 5,
        labelFormat					: d3.format('d'),
        labelInset					: 10,

        arcColorFn					: d3.interpolateHsl(d3.rgb('#ff9900'), d3.rgb('#cc3300'))
      };
      var range = undefined;
      var r = undefined;
      var pointerHeadLength = undefined;
      var value = 0;

      var svg = undefined;
      var arc = undefined;
      var scale = undefined;
      var ticks = undefined;
      var tickData = undefined;
      var pointer = undefined;

      var donut = d3.pie();

      function deg2rad(deg) {
        return deg * Math.PI / 180;
      }

      function newAngle(d) {
        var ratio = scale(d);
        var newAngle = config.minAngle + (ratio * range);
        return newAngle;
      }

      function configure(configuration) {
        var prop = undefined;
        for ( prop in configuration ) {
          config[prop] = configuration[prop];
        }

        range = config.maxAngle - config.minAngle;
        r = config.size / 2;
        pointerHeadLength = Math.round(r * config.pointerHeadLengthPercent);

        // a linear scale that maps domain values to a percent from 0..1
        scale = d3.scaleLinear()
          .range([0,1])
          .domain([config.minValue, config.maxValue]);

        ticks = scale.ticks(config.majorTicks);
        tickData = d3.range(config.majorTicks).map(function() {return 1/config.majorTicks;});

        arc = d3.arc()
          .innerRadius(r - config.ringWidth - config.ringInset)
          .outerRadius(r - config.ringInset)
          .startAngle(function(d, i) {
            var ratio = d * i;
            return deg2rad(config.minAngle + (ratio * range));
          })
          .endAngle(function(d, i) {
            var ratio = d * (i+1);
            return deg2rad(config.minAngle + (ratio * range));
          });
      }
      that.configure = configure;

      function centerTranslation() {
        return 'translate('+r +','+ r +')';
      }

      function isRendered() {
        return (svg !== undefined);
      }
      that.isRendered = isRendered;

      function render(newValue) {
        svg = d3.select(container)
          .append('svg:svg')
            .attr('class', 'gauge')
            .attr('width', config.clipWidth)
            .attr('height', config.clipHeight);

        var centerTx = centerTranslation();

        var arcs = svg.append('g')
            .attr('class', 'arc')
            .attr('transform', centerTx);

        arcs.selectAll('path')
            .data(tickData)
          .enter().append('path')
            .attr('fill', function(d, i) {
              return config.arcColorFn(d * i);
            })
            .attr('d', arc);

        var lg = svg.append('g')
            .attr('class', 'label')
            .attr('transform', centerTx);
        lg.selectAll('text')
            .data(ticks)
          .enter().append('text')
            .attr('transform', function(d) {
              var ratio = scale(d);
              var newAngle = config.minAngle + (ratio * range);
              return 'rotate(' +newAngle +') translate(0,' +(config.labelInset - r) +')';
            })
            .text(config.labelFormat);

        var lineData = [ [config.pointerWidth / 2, 0],
                [0, -pointerHeadLength],
                [-(config.pointerWidth / 2), 0],
                [0, config.pointerTailLength],
                [config.pointerWidth / 2, 0] ];
        var pointerLine = d3.line().curve(d3.curveLinear)
        var pg = svg.append('g').data([lineData])
            .attr('class', 'pointer')
            .attr('transform', centerTx);

        pointer = pg.append('path')
          .attr('d', pointerLine/*function(d) { return pointerLine(d) +'Z';}*/ )
          .attr('transform', 'rotate(' +config.minAngle +')');

        update(newValue === undefined ? 0 : newValue);
      }
      that.render = render;
      function update(newValue, newConfiguration) {
        if ( newConfiguration  !== undefined) {
          configure(newConfiguration);
        }
        var ratio = scale(newValue);
        var newAngle = config.minAngle + (ratio * range);
        pointer.transition()
          .duration(config.transitionMs)
          .ease(d3.easeElastic)
          .attr('transform', 'rotate(' +newAngle +')');
      }
      that.update = update;

      configure(configuration);

      return that;
    };

    $(function(){
      //alert('start')
      var oldEquip = 1100;
      var gWidth = $("#power-gauge").css("width").replace("px","")*0.8;  //400
      if(gWidth > 400){
        gWidth =400;
      }
      var gHeight = (gWidth /2) +20;  //200
      var powerGauge = gauge('#power-gauge', {
      size: gWidth,
      clipWidth: gWidth,
      clipHeight: gHeight,
      ringWidth: 60,
      maxValue: oldEquip*1.2,
      transitionMs: 4000,
      arcColorFn: d3.interpolateHsl(d3.rgb('#DEA82C'), d3.rgb('#e92213')),
      majorTicks					: 3,
      pointerWidth				: 20,
      pointerTailLength			: 5,
      pointerHeadLengthPercent	: 0.5,
      });
      powerGauge.render();

      /*//renew
      $("#power-gauge").html("");
      powerGauge = gauge('#power-gauge', {
      size: 400,
      clipWidth: 400,
      clipHeight: 250,
      ringWidth: 60,
      maxValue: oldEquip*1.2,
      transitionMs: 4000,
      });
      powerGauge.render();*/


      $("#btnOLD").click(function(){
        powerGauge.update(1100);
        store.state.KWH = 1100;
      })
      $("#btnOLD").hover(function(){
        $(this).click()
      })

      $("#btnNEW").click(function(){
        powerGauge.update(800);
        store.state.KWH = 800;
      })
      $("#btnNEW").hover(function(){
        $(this).click()
      })

      function updateReadings() {
        // just pump in random data here...
        powerGauge.update(Math.random() * 10);
    	}

	// every few seconds update reading values
	/*updateReadings();
	setInterval(function() {
		updateReadings();
	}, 5 * 1000);*/
      //alert('done')
    });
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped >
  #power-gauge g.arc {
		fill: steelblue;
	}

	#power-gauge g.pointer {
		fill: #464A4F;
		stroke: #fff;
	}

	#power-gauge g.label text {
		text-anchor: middle;
		font-size: 14px;
		font-weight: bold;
		fill: #666;
	}

</style>
