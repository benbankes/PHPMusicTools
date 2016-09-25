#(set-global-staff-size 20.16)
\paper {    

    #(define fonts
        (set-global-fonts
            #:roman "Century Gothic"
            #:music "haydn"
            #:brace "haydn"
            #:factor (/ staff-height pt 20)
        )
    ) 

    paper-width = 21.59\cm
    paper-height = 27.94\cm
    top-margin = 1.0\cm
    bottom-margin = 2.0\cm
    left-margin = 2.0\cm
    right-margin = 1.0\cm
    indent = 0\cm
    short-indent = 0\cm
}
\layout {
    \context { \Score
        skipBars = ##t
        autoBeaming = ##f
    }
}
