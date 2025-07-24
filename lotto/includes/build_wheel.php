<?php
# Lottery Wheeling (programmed for gimplearn.net)
# 18 Wheel, if match 5, win 3 with 9 tickets (7 numbers per ticket)
wheel = 'ABCDEFG HIJKLMO AHIPQRS BJKLPQR CDEFMOS AGMOPQR CDEFGHI BGHJKLS BGHIMOS'



uniques = sorted(list(set(wheel.replace(" ",""))))
wheelnums = len(uniques)
wheels = wheel.split(" ")
nums = input("Enter your "+str(wheelnums)+" numers to wheel (separated by spaces):")
nums = nums.replace(","," ").replace("-"," ").split(" ")
if len(nums) != wheelnums:
    print ("ERROR: Need "+str(wheelnums)+" numbers. You entered " + str(len(nums)) + " numbers!")

print ("You've entered: " + " ".join(nums))
print ("Wheel: " + wheel)
print ("============ Your lines ==============")
for wh in wheels:
    line = []
    for c in wh:
        index = uniques.index(c)
        line.append(nums[index])
    print (" ".join(line))
?>
