var transitionDefaultData = {
	"3d" : [
		{
			"name" : "Turning cuboid to right",
			"rows" : 1,
			"cols" : 1,
			"tile" : {
				"delay" : 75,
				"sequence" : "forward"
			},
			"animation" : {
				"duration" : 1,
				"easing" : "easeInOutQuart",
				"direction" : "horizontal",
				"transition" : {
					"rotateY" : 90
				}
			}
		},
		{
			"name" : "Vertical spinning rows random",
			"rows" : [3,7],
			"cols" : 1,
			"tile" : {
				"delay" : 75,
				"sequence" : "random"
			},
			"animation" : {
				"duration" : 2,
				"easing" : "easeInOutQuart",
				"direction" : "vertical",
				"transition" : {
					"rotateX" : 90
				}
			}
		},
		{
			"name" : "Scaling and spinning columns to left",
			"rows" : 1,
			"cols" : [7,11],
			"tile" : {
				"delay" : 75,
				"sequence" : "reverse"
			},
			"before" : {
				"enabled" : "on",
				"duration" : 2,
				"easing" : "easeOutBack",
				"transition" : {
					"scale3d" : 0.8
				}
			},
			"animation" : {
				"duration" : 1,
				"easing" : "easeInOutQuart",
				"direction" : "horizontal",
				"transition" : {
					"rotateY" : 90
				}
			},
			"after" : {
				"enabled" : "on",
				"duration" : 2,
				"easing" : "easeOutBack",
				"transition" : {
					"delay" : 200,
					"scale3d" : 1
				}
			}
		},
		{
			"name" : "Scaling and horizontal spinning cuboid random",
			"rows" : [2,4],
			"cols" : [4,7],
			"tile" : {
				"delay" : 75,
				"sequence" : "random",
				"depth" : "large"
			},
			"befor" : {
				"enabled" : "on",
				"duration" : 2,
				"easing" : "easeInoutQuint",
				"transition" : {
					"scale3d" : 0.9
				}
			},
			"animation" : {
				"duration" : 2,
				"easing" : "easeInOutQuart",
				"direction" : "horizontal",
				"transition" : {
					"rotateY" : 90
				}
			},
			"after" : {
				"enabled" : "on",
				"duration" : 2,
				"easing" : "easeInOutBack",
				"transition" : {
					"scale3d" : 1
				}
			}
		},
		{
			"name" : "Scaling and spinning rows to right (180&#176;)",
            "rows" : [5,9],
            "cols" : 1,
            "tile" : {
                "delay" : 75,
                "sequence" : "forward"
            },
            "before" : {
                "enabled" : "on",
                "duration" : 2,
                "easing" : "easeOutBack",
                "transition" : {
                    "scale3d" : 0.8
                }
            },
            "animation" : {
                "duration" : 1,
                "easing" : "easeInOutBack",
                "direction" : "horizontal",
                "transition" : {
                    "rotateY" : 90
                }
            },
            "after" : {
                "enabled" : "on",
                "duration" : 2,
                "easing" : "easeOutBack",
                "transition" : {
                    "delay" : 200,
                    "scale3d" : 1
                }
            }
		}
	],

	"2d" : [
		{
            "name" : "Sliding from right",
            "rows" : 1,
            "cols" : 1,
            "tile" : {
                "delay" : 0,
                "sequence" : "forward"
            },
            "transition" : {
                "type" : "slide",
                "easing" : "easeInOutQuart",
                "duration" : 1,
                "direction" : "left"
            }
        },

        {
            "name" : "Smooth fading from right",
            "rows" : 1,
            "cols" : 35,
            "tile" : {
                "delay" : 75,
                "sequence" : "reverse"
            },
            "transition" : {
                "type" : "fade",
                "easing" : "linear",
                "duration" : 0,
                "direction" : "left"
            }
        },

        {
            "name" : "Sliding random tiles to random directions",
            "rows" : [2,4],
            "cols" : [4,7],
            "tile" : {
                "delay" : 75,
                "sequence" : "random"
            },
            "transition" : {
                "type" : "slide",
                "easing" : "easeOutQuart",
                "duration" : 1,
                "direction" : "random"
            }
        },

        {
        	"name" : "Fading tiles col-forward",
            "rows" : [2,4],
            "cols" : [4,7],
            "tile" : {
                "delay" : 75,
                "sequence" : "col-forward"
            },
            "transition" : {
                "type" : "fade",
                "easing" : "easeOutQuart",
                "duration" : 1,
                "direction" : "left"
            }
        },

        {
            "name" : "Fading and sliding columns to bottom (forward)",
            "rows" : 1,
            "cols" : [12,16],
            "tile" : {
                "delay" : 75,
                "sequence" : "forward"
            },
            "transition" : {
                "type" : "mixed",
                "easing" : "easeInOutQuart",
                "duration" : 2,
                "direction" : "bottom"
            }
        }
	]
};